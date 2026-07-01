<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardStatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function __invoke(DashboardStatsService $stats): View
    {
        return view('admin.dashboard', [
            'overview' => $stats->overview(),
            'platformOverview' => $stats->platformOverview(),
            'topModels' => $stats->topUsedModels(),
            'recentGenerations' => $stats->recentGenerations(),
            'recentSubscriptions' => $stats->recentSubscriptions(),
            'systemHealth' => $stats->systemHealth(),
        ]);
    }
}
