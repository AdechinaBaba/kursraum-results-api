<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use App\Services\ResultService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ExamSessionPdfController extends Controller
{
    public function export(ExamSession $session)
    {
        try {
            // Vérifier les droits
            $this->authorize('export', $session);

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

            // Vérifier qu'il y a des résultats
            if ($results->isEmpty()) {
                return response()->json([
                    'message' => 'Aucun résultat à exporter pour cette session.'
                ], 404);
            }

            $pdf = Pdf::loadView('pdf.exam-session-results', [
                'session' => $session,
                'results' => $results
            ]);

            return $pdf->download('results-session-'.$session->id.'.pdf');

        } catch (\Exception $e) {
            // Logger l'erreur
            Log::error('Erreur export PDF:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Erreur lors de l\'export PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}