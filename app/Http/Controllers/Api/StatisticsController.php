<?php

namespace App\Http\Controllers\Api;

use App\Models\Center;
use App\Models\ExamSession;
use App\Services\StatisticsService;

class StatisticsController extends Controller
{
    public function sessionStats(ExamSession $session)
    {
        $service = app(StatisticsService::class);

        return response()->json([
            'overview' => $service->sessionOverview($session),
            'module_averages' => $service->moduleAverages($session),
        ]);
    }

    public function centerStats(Center $center)
    {
        $service = app(StatisticsService::class);

        return response()->json(
            $service->centerOverview($center)
        );
    }

    public function globalStats()
    {
        $service = app(StatisticsService::class);

        return response()->json(
            $service->globalOverview()
        );
    }
}
