<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdatePlatformRequest;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingsService;

    /**
     * Create a new controller instance.
     */
    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Display the settings page.
     */
    public function index()
    {
        $allPlatforms = $this->settingsService->getAllPlatforms();
        $userPlatforms = $this->settingsService->getUserPlatforms();

        return view('settings.index', [
            'platforms' => $allPlatforms,
            'userPlatforms' => $userPlatforms
        ]);
    }

    /**
     * Update platform settings.
     */
    public function updatePlatform(UpdatePlatformRequest $request, string $platform)
    {
        // dd($request->all());
        try {
            $userPlatform = $this->settingsService->updatePlatformSettings($platform, $request->validated());
            return redirect()->route('settings.index')
                ->with('success', ucfirst($platform) . ' connection settings have been updated.');
        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Failed to update ' . ucfirst($platform) . ' settings: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect a platform.
     */
    public function disconnectPlatform(string $platform)
    {
        try {
            $this->settingsService->disconnectPlatform($platform);
            return redirect()->route('settings.index')
                ->with('success', ucfirst($platform) . ' has been disconnected.');
        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Failed to disconnect ' . ucfirst($platform) . ': ' . $e->getMessage());
        }
    }
}
