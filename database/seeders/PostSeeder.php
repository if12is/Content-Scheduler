<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Platform;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure we have platforms
        if (Platform::count() === 0) {
            $this->call(PlatformSeeder::class);
        }

        // Get the test user
        $user = User::where('email', 'test@example.com')->first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Get platforms
        $platforms = Platform::all();

        // Create posts with different statuses and scheduled times
        $this->createScheduledPosts($user, $platforms);
        $this->createDraftPosts($user, $platforms);
        $this->createPublishedPosts($user, $platforms);
    }

    /**
     * Create posts scheduled for future publication
     */
    private function createScheduledPosts($user, $platforms)
    {
        // Create posts scheduled for the future - some today, some this week
        $scheduledTimes = [
            Carbon::now()->addMinutes(5),
            Carbon::now()->addMinutes(15),
            Carbon::now()->addMinutes(30),
            Carbon::now()->addHours(1),
            Carbon::now()->addDays(1),
            Carbon::now()->addDays(2),
            Carbon::now()->addDays(3),
            Carbon::now()->addDays(7),
        ];

        foreach ($scheduledTimes as $index => $scheduledTime) {
            $post = Post::create([
                'title' => 'Scheduled Post #' . ($index + 1),
                'content' => 'This is a scheduled post that will be published at ' . $scheduledTime->format('F j, Y, g:i a'),
                'image_url' => $index % 2 === 0 ? 'https://via.placeholder.com/800x600.png?text=Sample+Image+' . ($index + 1) : null,
                'scheduled_time' => $scheduledTime,
                'status' => 'scheduled',
                'user_id' => $user->id,
            ]);

            // Attach random platforms (1-3)
            $selectedPlatforms = $platforms->random(rand(1, min(3, $platforms->count())));
            $platformIds = [];
            foreach ($selectedPlatforms as $platform) {
                $platformIds[$platform->id] = ['platform_status' => 'pending'];
            }
            $post->platforms()->attach($platformIds);
        }
    }

    /**
     * Create posts in draft status
     */
    private function createDraftPosts($user, $platforms)
    {
        // Create some draft posts
        for ($i = 0; $i < 5; $i++) {
            $post = Post::create([
                'title' => 'Draft Post #' . ($i + 1),
                'content' => 'This is a draft post that needs to be scheduled or edited.',
                'image_url' => $i % 2 === 0 ? 'https://via.placeholder.com/800x600.png?text=Draft+Image+' . ($i + 1) : null,
                'scheduled_time' => null,
                'status' => 'draft',
                'user_id' => $user->id,
            ]);

            // Attach random platforms (0-2) - drafts might not have platforms selected yet
            if (rand(0, 1)) {
                $selectedPlatforms = $platforms->random(rand(1, min(2, $platforms->count())));
                $platformIds = [];
                foreach ($selectedPlatforms as $platform) {
                    $platformIds[$platform->id] = ['platform_status' => 'pending'];
                }
                $post->platforms()->attach($platformIds);
            }
        }
    }

    /**
     * Create posts that have already been published
     */
    private function createPublishedPosts($user, $platforms)
    {
        // Create some published posts (in the past)
        $publishedTimes = [
            Carbon::now()->subHours(2),
            Carbon::now()->subHours(6),
            Carbon::now()->subDays(1),
            Carbon::now()->subDays(2),
            Carbon::now()->subWeek(),
        ];

        foreach ($publishedTimes as $index => $publishedTime) {
            $post = Post::create([
                'title' => 'Published Post #' . ($index + 1),
                'content' => 'This post was published on ' . $publishedTime->format('F j, Y, g:i a'),
                'image_url' => $index % 2 === 0 ? 'https://via.placeholder.com/800x600.png?text=Published+Image+' . ($index + 1) : null,
                'scheduled_time' => $publishedTime,
                'status' => 'published',
                'user_id' => $user->id,
            ]);

            // All published posts have platforms
            $selectedPlatforms = $platforms->random(rand(1, min(3, $platforms->count())));
            $platformIds = [];
            foreach ($selectedPlatforms as $platform) {
                $platformIds[$platform->id] = ['platform_status' => 'published'];
            }
            $post->platforms()->attach($platformIds);
        }
    }
}
