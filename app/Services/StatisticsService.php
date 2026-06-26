<?php

namespace App\Services;

use App\Models\Center;
use App\Services\ResultService;

class StatisticsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sessionOverview(ExamSession $session): array
    {
        $results = $session->results;

        $totalCandidates = $results->count();

        $passed = 0;

        foreach ($results as $result) {

            if (app(ResultService::class)->isPassed($result)) {
                $passed++;
            }
        }

        $failed = $totalCandidates - $passed;

        return [

            'total_candidates' => $totalCandidates,

            'passed' => $passed,

            'failed' => $failed,

            'success_rate' => $totalCandidates > 0
                ? round(($passed / $totalCandidates) * 100, 2)
                : 0,
        ];
    }

    public function moduleAverages(ExamSession $session): array
    {
        return [
    
            'lesen' => round($session->results()->avg('lesen'), 2),
    
            'hoeren' => round($session->results()->avg('hoeren'), 2),
    
            'schreiben' => round($session->results()->avg('schreiben'), 2),
    
            'sprechen' => round($session->results()->avg('sprechen'), 2),
        ];
    }

    public function centerOverview(Center $center): array
    {
        $sessions = $center->examSessions()->with('results')->get();

        $totalCandidates = 0;
        $totalPassed = 0;

        foreach ($sessions as $session) {

            foreach ($session->results as $result) {

                $totalCandidates++;

                if (app(ResultService::class)->isPassed($result)) {
                    $totalPassed++;
                }
            }
        }

        $totalFailed = $totalCandidates - $totalPassed;

        return [
            'total_sessions' => $sessions->count(),
            'total_candidates' => $totalCandidates,
            'passed' => $totalPassed,
            'failed' => $totalFailed,
            'success_rate' => $totalCandidates > 0
                ? round(($totalPassed / $totalCandidates) * 100, 2)
                : 0,
        ];
    }

    public function globalOverview(): array
    {
        $sessions = \App\Models\ExamSession::with('results')->get();

        $totalCandidates = 0;
        $totalPassed = 0;

        foreach ($sessions as $session) {

            foreach ($session->results as $result) {

                $totalCandidates++;

                if (app(ResultService::class)->isPassed($result)) {
                    $totalPassed++;
                }
            }
        }

        return [
            'total_sessions' => $sessions->count(),
            'total_candidates' => $totalCandidates,
            'passed' => $totalPassed,
            'failed' => $totalCandidates - $totalPassed,
            'success_rate' => $totalCandidates > 0
                ? round(($totalPassed / $totalCandidates) * 100, 2)
                : 0,
        ];
    }

}
