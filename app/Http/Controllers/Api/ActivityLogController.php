<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the user's activity logs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->user()->activityLogs();

        // Filter by action
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        // Filter by entity type
        if ($request->has('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($logs);
    }

    /**
     * Get statistics about user activity.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        // Get post counts by status
        $postStats = [
            'draft' => $user->posts()->where('status', 'draft')->count(),
            'scheduled' => $user->posts()->where('status', 'scheduled')->count(),
            'published' => $user->posts()->where('status', 'published')->count(),
        ];

        // Get post counts by platform
        $platformStats = [];
        $platforms = $user->platforms;

        foreach ($platforms as $platform) {
            $platformStats[$platform->name] = [
                'total' => $user->posts()
                    ->whereHas('platforms', function ($query) use ($platform) {
                        $query->where('platforms.id', $platform->id);
                    })
                    ->count(),
                'published' => $user->posts()
                    ->where('status', 'published')
                    ->whereHas('platforms', function ($query) use ($platform) {
                        $query->where('platforms.id', $platform->id)
                            ->where('post_platforms.platform_status', 'published');
                    })
                    ->count(),
            ];
        }

        // Get activity counts by type
        $activityStats = ActivityLog::where('user_id', $user->id)
            ->selectRaw('action, count(*) as count')
            ->groupBy('action')
            ->pluck('count', 'action')
            ->toArray();

        return response()->json([
            'posts' => $postStats,
            'platforms' => $platformStats,
            'activities' => $activityStats,
        ]);
    }
}
