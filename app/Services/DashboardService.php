<?php

namespace App\Services;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPlatform;

class DashboardService
{
    /**
     * Get calendar data for the dashboard
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getCalendarData(int $month = null, int $year = null): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $date = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $firstDayOfMonth = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        // Get all posts for the current month
        $posts = Post::where('user_id', Auth::id())
            ->whereYear('scheduled_time', $year)
            ->whereMonth('scheduled_time', $month)
            ->with(['platforms'])
            ->get()
            ->groupBy(function ($post) {
                return Carbon::parse($post->scheduled_time)->day;
            });
        // dd($posts);
        return [
            'daysInMonth' => $daysInMonth,
            'firstDayOffset' => $firstDayOfMonth,
            'month' => $month,
            'year' => $year,
            'monthName' => $date->format('F'),
            'posts' => $posts
        ];
    }

    /**
     * Get upcoming posts for the dashboard list view
     *
     * @param string|null $platform Filter by platform
     * @param string|null $status Filter by status
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingPosts(?string $platform = null, ?string $status = null, int $limit = 10)
    {
        $query = Post::where('user_id', Auth::id())
            ->where('scheduled_time', '>=', Carbon::now())
            ->orderBy('scheduled_time', 'asc');

        if ($platform) {
            $query->whereHas('platforms', function ($q) use ($platform) {
                $q->where('name', $platform);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->with(['platforms' => function ($query) {
            $query->select('platforms.id', 'platforms.name');
        }])->limit($limit)->get();
    }

    /**
     * Get upcoming posts for the dashboard with pagination
     *
     * @param string|null $platform Filter by platform
     * @param string|null $status Filter by status
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUpcomingPostsPaginated(?string $platform = null, ?string $status = null, int $perPage = 10)
    {
        $query = Post::where('user_id', Auth::id())
            ->orderBy('scheduled_time', 'asc');

        if ($platform) {
            $query->whereHas('platforms', function ($q) use ($platform) {
                $q->whereHas('platform', function ($query) use ($platform) {
                    $query->where('name', $platform);
                });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->with(['platforms' => function ($query) {
            $query->select('platforms.id', 'platforms.name');
        }])->paginate($perPage);
    }

    /**
     * Get platform list for filters
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPlatforms()
    {
        return UserPlatform::where('user_id', Auth::id())
            ->with('platform')
            ->get()
            ->map(function ($userPlatform) {
                return $userPlatform->platform;
            })
            ->unique('id');
    }
}
