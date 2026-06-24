<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\ExamResult;
use App\Models\ExamSession;
use App\Services\ResultService;


class ExamResultsImport implements ToCollection
{
    public function __construct(
        private ExamSession $session,
        private ResultService $service
    ) {}

    public function collection(Collection $rows)
    {
        // skip header
        $rows->shift();

        foreach ($rows as $row) {

            ExamResult::updateOrCreate(
                [
                    'exam_session_id' => $this->session->id,
                    'table_number' => $row[0],
                ],
                [
                    'full_name' => $row[1],
                    'lesen' => $row[2],
                    'hoeren' => $row[3],
                    'schreiben' => $row[4],
                    'sprechen' => $row[5],
                ]
            );
        }
    }
}
