<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ResultService;
use App\Models\ExamResult;

class ExamResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $service = app(ResultService::class);
        $computed = $service->compute($this);

        return [
            'id' => $this->id,

            'candidate' => [
                'full_name' => $this->full_name,
                'table_number' => $this->table_number,
            ],

            'scores' => [
                'lesen' => $this->lesen,
                'hoeren' => $this->hoeren,
                'schreiben' => $this->schreiben,
                'sprechen' => $this->sprechen,
            ],

            'session' => [
                'id' => $this->examSession->id,
                'title' => $this->examSession->title,
                'level' => $this->examSession->level,
                'exam_date' => $this->examSession->exam_date,
                'module_max_score' => $this->examSession->module_max_score,
                'passing_percentage' => $this->examSession->passing_percentage,
                'published' => $this->examSession->published,
            ],

            'results' => [
                'total' => $computed['total'],
                'overall_passed' => $computed['overall_passed'],
                'module_passing_score' => $computed['module_passing_score'],
                'overall_passing_score' => $computed['overall_passing_score'],
            ],

            'modules_status' => $computed['modules'],

            'status_label' => $computed['overall_passed']
                ? 'Bestanden'
                : 'Nicht Bestanden',
        ];
    }
}