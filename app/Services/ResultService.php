<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\ExamResult;
use App\Http\Resources\ExamResultResource;

class ResultService
{
    /**
     * Liste des modules
     */
    private array $modules = [
        'lesen',
        'hoeren',
        'schreiben',
        'sprechen',
    ];

    /**
     * Total des notes
     */
    public function total(ExamResult $result): float
    {
        return array_sum(array_map(
            fn ($m) => $result->$m,
            $this->modules
        ));
    }

    /**
     * Note minimale pour un module
     */
    public function modulePassingScore(ExamSession $session): float
    {
        return ($session->module_max_score * $session->passing_percentage) / 100;
    }

    /**
     * Vérifie un module précis
     */
    public function isModulePassed(float $score, ExamSession $session): bool
    {
        return $score >= $this->modulePassingScore($session);
    }

    /**
     * Vérifie chaque module
     */
    public function modulesStatus(ExamResult $result): array
    {
        $session = $result->examSession;
        $passingScore = $this->modulePassingScore($session);

        $status = [];

        foreach ($this->modules as $module) {
            $status[$module] = [
                'score' => $result->$module,
                'passed' => $result->$module >= $passingScore,
            ];
        }

        return $status;
    }

    /**
     * Total requis pour réussir l'examen
     */
    public function overallPassingScore(ExamSession $session): float
    {
        $maxTotal = $session->module_max_score * count($this->modules);

        return $maxTotal * ($session->passing_percentage / 100);
    }

    /**
     * Résultat final
     */
    public function isPassed(ExamResult $result): bool
    {
        return $this->total($result)
            >= $this->overallPassingScore($result->examSession);
    }

    /**
     * Résultat complet prêt pour API
     */
    public function compute(ExamResult $result): array
    {
        $session = $result->examSession;

        return [
            'total' => $this->total($result),

            'overall_passed' => $this->isPassed($result),

            'modules' => $this->modulesStatus($result),

            'module_passing_score' => $this->modulePassingScore($session),

            'overall_passing_score' => $this->overallPassingScore($session),

            'percentage' => $this->percentage($result),

            'mention' => $this->mention($result),
        ];
    }

    public function percentage(ExamResult $result): float
    {
        $session = $result->examSession;

        $maxTotal = $session->module_max_score * count($this->modules);

        return round(
            ($this->total($result) / $maxTotal) * 100,
            2
        );
    }

    public function mention(ExamResult $result): string
    {
        if (!$this->isPassed($result)) {
            return 'Nicht Bestanden';
        }
    
        $percentage = $this->percentage($result);
    
        return match (true) {
            $percentage >= 85 => 'Sehr Gut',
            $percentage >= 70 => 'Gut',
            $percentage >= 60 => 'Befriedigend',
            default => 'Ausreichend',
        };
    }
}