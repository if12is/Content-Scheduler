<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\PostPublishingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all scheduled posts that are due for publishing';

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
        $this->info('Starting to process scheduled posts...');

        // Get all posts that are scheduled and due for publishing
        $posts = Post::dueForPublishing()->get();

        $count = $posts->count();
        $this->info("Found {$count} posts due for publishing");

        if ($count === 0) {
            return 0;
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($posts as $post) {
            $this->info("Processing post ID: {$post->id}, Title: {$post->title}");

            try {
                $result = $this->publishingService->publishPost($post);

                if ($result['success']) {
                    $this->info("✓ Successfully published post {$post->id}");
                    $successCount++;
                } else {
                    $this->warn("⚠ Partially published post {$post->id}: {$result['message']}");
                    $failCount++;
                }

                // Output details for each platform
                if (isset($result['details'])) {
                    foreach ($result['details'] as $platformName => $platformResult) {
                        $status = $platformResult['success'] ? '✓' : '✗';
                        $this->line("  {$status} {$platformName}: {$platformResult['message']}");
                    }
                }
            } catch (\Exception $e) {
                $this->error("✗ Failed to process post {$post->id}: {$e->getMessage()}");
                Log::error("Failed to process post {$post->id}: {$e->getMessage()}");
                $failCount++;
            }
        }

        $this->newLine();
        $this->info("Completed processing scheduled posts");
        $this->info("Success: {$successCount}, Failed: {$failCount}");

        return 0;
    }
}
