<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\PostPublishingService;
use Illuminate\Console\Command;

class TestPostPublishing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-publishing {post_id? : ID of the post to test publish}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the publishing process on a specific post or a random scheduled post';

    /**
     * The publishing service.
     *
     * @var PostPublishingService
     */
    protected $publishingService;

    /**
     * Create a new command instance.
     */
    public function __construct(PostPublishingService $publishingService)
    {
        parent::__construct();
        $this->publishingService = $publishingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $postId = $this->argument('post_id');

        if ($postId) {
            $post = Post::find($postId);
            if (!$post) {
                $this->error("No post found with ID: {$postId}");
                return 1;
            }
        } else {
            // Get a random scheduled post
            $post = Post::where('status', 'scheduled')
                ->inRandomOrder()
                ->first();

            if (!$post) {
                $this->error("No scheduled posts found to test");
                return 1;
            }
        }

        $this->info("Testing publishing for post ID: {$post->id}, Title: {$post->title}");
        $this->info("Status: {$post->status}, Scheduled: {$post->scheduled_time}");

        $platforms = $post->platforms;
        if ($platforms->isEmpty()) {
            $this->error("This post has no platforms to publish to");
            return 1;
        }

        $this->info("Publishing to " . $platforms->count() . " platforms:");
        foreach ($platforms as $platform) {
            $this->info("- " . $platform->name);
        }

        if (!$this->confirm("Proceed with test publishing?", true)) {
            $this->info("Test cancelled");
            return 0;
        }

        try {
            $result = $this->publishingService->publishPost($post);

            $this->newLine();
            if ($result['success']) {
                $this->info("✓ Successfully published post {$post->id}");
            } else {
                $this->warn("⚠ Partially published post {$post->id}: {$result['message']}");
            }

            // Output details for each platform
            if (isset($result['details'])) {
                $this->info("Publishing details:");
                foreach ($result['details'] as $platformName => $platformResult) {
                    $status = $platformResult['success'] ? '✓' : '✗';
                    $this->line("  {$status} {$platformName}: {$platformResult['message']}");

                    // Show response data if available
                    if ($platformResult['success'] && isset($platformResult['response_data'])) {
                        $this->line("    Response data:");
                        foreach ($platformResult['response_data'] as $key => $value) {
                            $this->line("      {$key}: {$value}");
                        }
                    }
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("✗ Failed to process post {$post->id}: {$e->getMessage()}");
            return 1;
        }
    }
}
