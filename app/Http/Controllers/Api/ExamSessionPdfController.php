<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ResultService;

class ExamSessionPdfController extends Controller
{
    public function export(ExamSession $session)
    {
        $this->authorize('view', $session);

        $session->load(['results']);

        $service = app(ResultService::class);

        $results = $session->results->map(function ($result) use ($service) {

            return [
                'table_number' => $result->table_number,
                'full_name' => $result->full_name,

                'lesen' => $result->lesen,
                'hoeren' => $result->hoeren,
                'schreiben' => $result->schreiben,
                'sprechen' => $result->sprechen,

                'total' => $service->total($result),

                'status' => $service->isPassed($result)
                    ? 'Bestanden'
                    : 'Nicht Bestanden',
            ];
        });

        $pdf = Pdf::loadView('pdf.exam-session-results', [
            'session' => $session,
            'results' => $results
        ]);

        return $pdf->download('results-session-'.$session->id.'.pdf');
    }
}