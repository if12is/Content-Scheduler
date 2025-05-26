<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard with calendar and upcoming posts.
     */
    public function index(Request $request)
    {
        $month = $request->query('month') ? (int)$request->query('month') : null;
        $year = $request->query('year') ? (int)$request->query('year') : null;
        $platform = $request->query('platform');
        $status = $request->query('status');

        $calendarData = $this->dashboardService->getCalendarData($month, $year);
        $posts = $this->dashboardService->getUpcomingPostsPaginated($platform, $status, 10);
        $platforms = $this->dashboardService->getPlatforms();
        return view('dashboard.index', [
            'calendarData' => $calendarData,
            'posts' => $posts,
            'platforms' => $platforms
        ]);
    }
}
