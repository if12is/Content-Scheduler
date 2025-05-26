<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of all available platforms.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $platforms = Platform::all();
        return response()->json($platforms);
    }

    /**
     * Display a listing of platforms active for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPlatforms(Request $request)
    {
        $platforms = $request->user()->platforms;
        return response()->json($platforms);
    }

    /**
     * Toggle a platform's active status for the authenticated user.
     *
     * @param Request $request
     * @param int $platformId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request, $platformId)
    {
        $platform = Platform::findOrFail($platformId);
        $user = $request->user();

        // Check if the user has this platform
        $userPlatform = $user->platforms()->where('platform_id', $platformId)->first();

        if ($userPlatform) {
            // Toggle the active status
            $isActive = !$userPlatform->pivot->is_active;
            $user->platforms()->updateExistingPivot($platformId, ['is_active' => $isActive]);

            $action = $isActive ? 'activated' : 'deactivated';
            $this->activityLogService->log(
                $action,
                'platform',
                $platform->id,
                "User {$user->name} {$action} platform {$platform->name}"
            );

            return response()->json([
                'message' => "Platform {$platform->name} has been " . ($isActive ? 'activated' : 'deactivated'),
                'is_active' => $isActive
            ]);
        } else {
            // Add the platform for the user
            $user->platforms()->attach($platformId, ['is_active' => true]);

            $this->activityLogService->log(
                'add',
                'platform',
                $platform->id,
                "User {$user->name} added platform {$platform->name}"
            );

            return response()->json([
                'message' => "Platform {$platform->name} has been added and activated",
                'is_active' => true
            ]);
        }
    }
}
