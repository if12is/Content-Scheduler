<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Platform;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\StorePostRequest;
use App\Http\Requests\Api\UpdatePostRequest;

class PostController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of the posts.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->user()->posts();

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['draft', 'scheduled', 'published'])) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('scheduled_time', $request->date);
        }

        // Order by scheduled time
        $posts = $query->with('platforms')->orderBy('scheduled_time', 'desc')->paginate(10);

        return response()->json($posts);
    }

    /**
     * Store a newly created post.
     *
     * @param StorePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        // Check rate limiting (max 10 scheduled posts per day)
        $today = now()->startOfDay();
        $scheduledToday = $request->user()->posts()
            ->where('status', 'scheduled')
            ->whereDate('created_at', $today)
            ->count();

        if ($scheduledToday >= 10) {
            return response()->json([
                'message' => 'You have reached the limit of 10 scheduled posts per day',
            ], 429);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('post-images', 'public');
        }

        // Create the post
        $post = $request->user()->posts()->create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image_url' => $imagePath ? Storage::url($imagePath) : null,
            'scheduled_time' => $data['scheduled_time'],
            'status' => $data['status'],
        ]);

        // Attach platform by name
        $platform = Platform::where('name', $data['platform'])->first();
        if ($platform) {
            $post->platforms()->attach($platform->id);
        }

        // Log the action
        $this->activityLogService->logCreate(
            'post',
            $post->id,
            "Post '{$post->title}' was created and scheduled",
            ['platform' => $data['platform']]
        );

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load('platforms'),
        ], 201);
    }

    /**
     * Display the specified post.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $post = Post::with('platforms')->findOrFail($id);

        // Check if the post belongs to the authenticated user
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }

    /**
     * Update the specified post.
     *
     * @param UpdatePostRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        // Check if the post belongs to the authenticated user
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Only allow updates to draft or scheduled posts
        if ($post->status === 'published') {
            return response()->json([
                'message' => 'Published posts cannot be updated',
            ], 403);
        }

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image_url')) {
            // Delete old image if exists
            if ($post->image_url) {
                $oldPath = str_replace('/storage/', '', $post->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image_url')->store('post-images', 'public');
            $post->image_url = Storage::url($imagePath);
        }

        // Update post fields
        if (isset($data['title'])) {
            $post->title = $data['title'];
        }
        if (isset($data['content'])) {
            $post->content = $data['content'];
        }
        if (isset($data['scheduled_time'])) {
            $post->scheduled_time = $data['scheduled_time'];
        }
        if (isset($data['status'])) {
            $post->status = $data['status'];
        }

        $post->save();

        // Update platform if provided
        if (isset($data['platform'])) {
            $platform = \App\Models\Platform::where('name', $data['platform'])->first();
            if ($platform) {
                $post->platforms()->sync([$platform->id]);
            }
        }

        // Log the action
        $this->activityLogService->logUpdate(
            'post',
            $post->id,
            "Post '{$post->title}' was updated",
            ['updated_fields' => array_keys($data)]
        );

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post->load('platforms'),
        ]);
    }

    /**
     * Remove the specified post.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Check if the post belongs to the authenticated user
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete image if exists
        if ($post->image_url) {
            $imagePath = str_replace('/storage/', '', $post->image_url);
            Storage::disk('public')->delete($imagePath);
        }

        // Log before deletion to capture post title
        $this->activityLogService->logDelete(
            'post',
            $post->id,
            "Post '{$post->title}' was deleted",
            ['scheduled_time' => $post->scheduled_time]
        );

        // Delete the post (will cascade to post_platforms)
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
