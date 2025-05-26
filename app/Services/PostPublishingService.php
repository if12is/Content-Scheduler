<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Platform;
use App\Models\PostPlatform;
use Illuminate\Support\Facades\Log;

class PostPublishingService
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Publish a post to all selected platforms.
     *
     * @param Post $post
     * @return array
     */
    public function publishPost(Post $post): array
    {
        $results = [];

        // Only publish posts that are scheduled and due
        if ($post->status !== 'scheduled' || $post->scheduled_time > now()) {
            return ['success' => false, 'message' => 'Post is not ready for publishing'];
        }

        // Get all platforms for this post
        $postPlatforms = $post->platforms;

        foreach ($postPlatforms as $platform) {
            $result = $this->publishToSinglePlatform($post, $platform);
            $results[$platform->name] = $result;

            // Update the pivot table with the status
            $post->platforms()->updateExistingPivot(
                $platform->id,
                ['platform_status' => $result['success'] ? PostPlatform::STATUS_PUBLISHED : PostPlatform::STATUS_FAILED]
            );
        }

        // If all platforms were published successfully, update the post status
        $allSuccess = collect($results)->every(fn($result) => $result['success']);

        if ($allSuccess) {
            $post->update(['status' => 'published']);

            // Log the activity
            $this->activityLogService->logPublish(
                'post',
                $post->id,
                "Post '{$post->title}' was published successfully to all platforms",
                ['platforms' => $postPlatforms->pluck('name')->toArray()]
            );
        } else {
            // Log the partial success
            $this->activityLogService->logPublish(
                'post',
                $post->id,
                "Post '{$post->title}' was published with some failures",
                [
                    'platforms' => $postPlatforms->pluck('name')->toArray(),
                    'results' => $results
                ]
            );
        }

        return [
            'success' => $allSuccess,
            'message' => $allSuccess ? 'Post published successfully to all platforms' : 'Post published with some failures',
            'details' => $results
        ];
    }

    /**
     * Schedule a post to be published at a specific time.
     *
     * @param int|Post $post Post ID or Post object
     * @return bool
     */
    public function schedulePost($post): bool
    {
        if (is_numeric($post)) {
            $post = Post::find($post);
        }

        if (!$post || !($post instanceof Post)) {
            return false;
        }

        $post->update(['status' => 'scheduled']);
        return true;
    }

    /**
     * Publish a post to a single platform.
     *
     * This is a mock implementation that simulates publishing to different platforms.
     *
     * @param Post $post
     * @param Platform $platform
     * @return array
     */
    protected function publishToSinglePlatform(Post $post, Platform $platform): array
    {
        // In a real application, this would use the appropriate API for each platform
        try {
            // Simulate platform-specific validation
            $this->validateForPlatform($post, $platform);

            // Log the attempt
            Log::info("Publishing post {$post->id} to {$platform->name} ({$platform->type})", [
                'post_title' => $post->title,
                'content_length' => strlen($post->content),
                'has_image' => !empty($post->image_url),
                'scheduled_time' => $post->scheduled_time->format('Y-m-d H:i:s')
            ]);

            // Platform-specific success rates to make testing more realistic
            $successRates = [
                'twitter' => 90,  // 90% success
                'instagram' => 85,
                'facebook' => 95,
                'linkedin' => 92,
                'default' => 80
            ];

            $successRate = $successRates[$platform->type] ?? $successRates['default'];
            $success = rand(1, 100) <= $successRate;

            if (!$success) {
                // Generate platform-specific errors
                $errorMessages = [
                    'twitter' => ['Rate limit exceeded', 'Authentication error', 'Content policy violation'],
                    'instagram' => ['Image upload failed', 'API temporarily unavailable', 'Content flagged for review'],
                    'facebook' => ['Page access token expired', 'Permission denied', 'Content blocked by community standards'],
                    'linkedin' => ['Profile access restricted', 'Content format not supported', 'Connection error'],
                    'default' => ['API error', 'Connection timeout', 'Unknown error']
                ];

                $errors = $errorMessages[$platform->type] ?? $errorMessages['default'];
                $error = $errors[array_rand($errors)];

                throw new \Exception("{$error} on {$platform->name}");
            }

            // Simulate realistic response data from different platforms
            $responseData = match ($platform->type) {
                'twitter' => [
                    'tweet_id' => 'tw_' . substr(md5($post->id . time()), 0, 15),
                    'impression_count' => rand(10, 500),
                ],
                'instagram' => [
                    'media_id' => 'ig_' . substr(md5($post->id . time()), 0, 15),
                    'carousel_id' => empty($post->image_url) ? null : 'car_' . substr(md5(time()), 0, 10),
                ],
                'facebook' => [
                    'post_id' => 'fb_' . substr(md5($post->id . time()), 0, 15),
                    'page_id' => 'page_' . rand(1000000, 9999999),
                ],
                'linkedin' => [
                    'update_id' => 'li_' . substr(md5($post->id . time()), 0, 15),
                    'organization_id' => 'org_' . rand(1000, 9999),
                ],
                default => [
                    'post_id' => 'gen_' . substr(md5($post->id . time()), 0, 15),
                ]
            };

            // Simulate a delay for the API call based on platform
            $delays = [
                'twitter' => [100000, 300000],
                'instagram' => [300000, 700000], // Instagram is slower
                'facebook' => [200000, 400000],
                'linkedin' => [150000, 350000],
                'default' => [100000, 500000]
            ];

            $platformDelays = $delays[$platform->type] ?? $delays['default'];
            usleep(rand($platformDelays[0], $platformDelays[1]));

            // Log success
            Log::info("Successfully published post {$post->id} to {$platform->name}", [
                'platform_type' => $platform->type,
                'response_data' => $responseData
            ]);

            return [
                'success' => true,
                'message' => "Successfully published to {$platform->name}",
                'platform_id' => $platform->id,
                'platform_type' => $platform->type,
                'response_data' => $responseData
            ];
        } catch (\Exception $e) {
            // Log failure with details
            Log::error("Failed to publish post {$post->id} to {$platform->name}: " . $e->getMessage(), [
                'exception' => get_class($e),
                'platform_type' => $platform->type,
                'post_title' => $post->title
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'platform_id' => $platform->id,
                'platform_type' => $platform->type
            ];
        }
    }

    /**
     * Validate that a post meets the requirements for a specific platform.
     *
     * @param Post $post
     * @param Platform $platform
     * @throws \Exception
     */
    protected function validateForPlatform(Post $post, Platform $platform): void
    {
        $characterLimit = $platform->getCharacterLimit();

        // Check content length against platform limits
        if (strlen($post->content) > $characterLimit) {
            throw new \Exception("Content exceeds the {$characterLimit} character limit for {$platform->name}");
        }

        // Platform-specific validations
        switch ($platform->type) {
            case 'twitter':
                // Twitter requires an image or content
                if (empty($post->content) && empty($post->image_url)) {
                    throw new \Exception("Twitter posts require either content or an image");
                }
                break;

            case 'instagram':
                // Instagram requires an image
                if (empty($post->image_url)) {
                    throw new \Exception("Instagram posts require an image");
                }
                break;

            case 'linkedin':
                // LinkedIn requires content
                if (empty($post->content)) {
                    throw new \Exception("LinkedIn posts require content");
                }
                break;
        }
    }
}
