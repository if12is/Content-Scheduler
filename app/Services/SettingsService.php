<?php

namespace App\Services;

use App\Models\Platform;
use App\Models\UserPlatform;
use Illuminate\Support\Facades\Auth;

class SettingsService
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Get all available platforms
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPlatforms()
    {
        return Platform::all();
    }

    /**
     * Get user connected platforms
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserPlatforms()
    {
        return UserPlatform::where('user_id', Auth::id())
            ->with('platform')
            ->get();
    }

    /**
     * Helper to get a credential for display in a form.
     * For sensitive fields, it returns '********' if set, otherwise empty.
     */
    public function getCredentialDisplay($credentials, $key, $sensitive = false)
    {
        if (!is_array($credentials)) {
            $credentials = json_decode($credentials, true) ?: [];
        }
        if ($sensitive && !empty($credentials[$key])) {
            return '********';
        }
        return $credentials[$key] ?? '';
    }

    /**
     * Update platform settings
     *
     * @param string $platformName
     * @param array $credentials
     * @return UserPlatform
     */
    public function updatePlatformSettings(string $platformName, array $credentials)
    {
        $platform = Platform::where('name', $platformName)->first();
        // dd($platform, $platformName, $credentials);
        if (!$platform) {
            throw new \Exception("Platform {$platformName} not found");
        }

        // Check if user already has this platform connected
        $userPlatform = UserPlatform::where('user_id', Auth::id())
            ->where('platform_id', $platform->id)
            ->first();

        $oldCreds = $userPlatform ? (is_array($userPlatform->credentials) ? $userPlatform->credentials : json_decode($userPlatform->credentials, true)) : [];
        $mergedCreds = $oldCreds;
        foreach ($credentials as $key => $value) {
            // If sensitive and value is empty or '********', keep old
            if (in_array($key, ['api_secret_key', 'access_token_secret', 'client_secret', 'app_secret', 'user_access_token']) && ($value === '' || $value === '********')) {
                continue;
            }
            $mergedCreds[$key] = $value;
        }

        if ($userPlatform) {
            // Update existing connection
            $userPlatform->update([
                'credentials' => json_encode($mergedCreds),
                'is_active' => true
            ]);
        } else {
            // Create new connection
            $userPlatform = UserPlatform::create([
                'user_id' => Auth::id(),
                'platform_id' => $platform->id,
                'credentials' => json_encode($mergedCreds),
                'is_active' => true
            ]);
        }

        // Log activity
        $this->activityLogService->log(
            'platform_connected',
            'platform',
            $platform->id,
            "Connected to {$platformName} platform",
        );

        return $userPlatform;
    }

    /**
     * Disconnect a platform
     *
     * @param string $platformName
     * @return bool
     */
    public function disconnectPlatform(string $platformName)
    {
        $platform = Platform::where('name', $platformName)->first();

        if (!$platform) {
            throw new \Exception("Platform {$platformName} not found");
        }

        $deleted = UserPlatform::where('user_id', Auth::id())
            ->where('platform_id', $platform->id)
            ->delete();

        if ($deleted) {
            // Log activity
            $this->activityLogService->log(
                'platform_disconnected',
                'platform',
                $platform->id,
                "Disconnected from {$platformName} platform"
            );
        }

        return (bool)$deleted;
    }
}
