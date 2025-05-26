<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Platform;
use App\Models\PostPlatform;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PostService
{
    protected $activityLogService;
    protected $postPublishingService;

    public function __construct(
        ActivityLogService $activityLogService,
        PostPublishingService $postPublishingService
    ) {
        $this->activityLogService = $activityLogService;
        $this->postPublishingService = $postPublishingService;
    }

    /**
     * Get all posts for the authenticated user
     *
     * @param string|null $platform Filter by platform
     * @param string|null $status Filter by status
     * @param string|null $search Search term
     * @param int $perPage Number of posts per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllPosts(string $platform = null, string $status = null, string $search = null, int $perPage = 10)
    {
        $query = Post::where('user_id', Auth::id())
            ->with(['platforms'])
            ->orderBy('scheduled_time', 'desc');

        // Apply filters
        if ($platform) {
            $query->whereHas('platforms.platform', function ($q) use ($platform) {
                $q->where('name', $platform);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get a post by ID
     *
     * @param int $id
     * @return Post|null
     */
    public function getPost(int $id)
    {
        return Post::where('user_id', Auth::id())
            ->with(['platforms'])
            ->findOrFail($id);
    }

    /**
     * Create a new post
     *
     * @param array $data
     * @return Post
     */
    public function createPost(array $data)
    {
        // Create the scheduled_time datetime from date and time
        $scheduledAt = Carbon::parse($data['scheduled_date'] . ' ' . $data['scheduled_time']);

        // Handle image upload if present
        $imageUrl = null;
        if (isset($data['image_url']) && $data['image_url'] instanceof UploadedFile) {
            $imageUrl = $this->uploadImage($data['image_url']);
        }

        // dd($imageUrl, $data);
        // Create post
        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image_url' => $imageUrl,
            'scheduled_time' => $scheduledAt,
            'status' => $data['status'],
            'user_id' => Auth::id()
        ]);

        // Attach platform
        $platform = Platform::where('name', $data['platform'])->first();
        if ($platform) {
            PostPlatform::create([
                'post_id' => $post->id,
                'platform_id' => $platform->id,
                'status' => 'pending'
            ]);
        }

        // Log activity
        $this->activityLogService->log(
            'post_created',
            'post',
            $post->id,
            'Created a new post',
            ['platform' => $data['platform'], 'status' => $data['status']]
        );

        // If status is scheduled and scheduled_time is due, queue for publishing
        if ($data['status'] === 'scheduled' && $scheduledAt->isPast()) {
            $this->postPublishingService->schedulePost($post);
        }

        return $post;
    }

    /**
     * Update a post
     *
     * @param int $id
     * @param array $data
     * @return Post
     */
    public function updatePost(int $id, array $data)
    {
        $post = $this->getPost($id);

        // Create the scheduled_time datetime from date and time
        $scheduledAt = Carbon::parse($data['scheduled_date'] . ' ' . $data['scheduled_time']);

        // Handle image upload if present
        if (isset($data['image_url']) && $data['image_url'] instanceof UploadedFile) {
            // Delete old image if exists and it's a stored file (not an external URL)
            if ($post->image_url && strpos($post->image_url, 'storage/') !== false) {
                Storage::delete(str_replace('storage/', '', $post->image_url));
            }

            $imageUrl = $this->uploadImage($data['image_url']);
            $post->image_url = $imageUrl;
        }

        // Update post data
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->scheduled_time = $scheduledAt;
        $post->status = $data['status'];
        $post->save();

        // Update platform if changed
        $platformChanged = false;
        $currentPlatform = $post->platforms->first()->name ?? null;

        if ($currentPlatform !== $data['platform']) {
            // Detach all current platforms
            PostPlatform::where('post_id', $post->id)->delete();

            // Attach new platform
            $platform = Platform::where('name', $data['platform'])->first();
            if ($platform) {
                PostPlatform::create([
                    'post_id' => $post->id,
                    'platform_id' => $platform->id,
                    'status' => 'pending'
                ]);
            }

            $platformChanged = true;
        }

        // Log activity
        $this->activityLogService->log(
            'post_updated',
            'post',
            $post->id,
            'Updated a post',
            [
                'platform_changed' => $platformChanged,
                'status' => $data['status'],
                'platform' => $data['platform']
            ]
        );

        // If status is scheduled and scheduled_time is due, queue for publishing
        if ($data['status'] === 'scheduled' && $scheduledAt->isPast()) {
            $this->postPublishingService->schedulePost($post);
        }

        return $post->refresh();
    }

    /**
     * Delete a post
     *
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id)
    {
        $post = $this->getPost($id);

        // Delete image if exists and it's a stored file (not an external URL)
        if ($post->image_url && strpos($post->image_url, 'storage/') !== false) {
            $path = str_replace(asset('storage/'), '', $post->image_url);
            Storage::disk('public')->delete($path);
        }

        // Log activity before deletion
        $this->activityLogService->log(
            'post_deleted',
            'post',
            $post->id,
            'Deleted a post'
        );

        // Delete post
        return $post->delete();
    }

    /**
     * Upload post image
     *
     * @param UploadedFile $image
     * @return string|null
     */
    protected function uploadImage(UploadedFile $image)
    {
        $path = $image->store('post-images', 'public');
        return $path ? 'storage/' . $path : null;
    }

    /**
     * Get platforms for creating/editing posts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPlatformsForSelection()
    {
        return Platform::all();
    }
}
