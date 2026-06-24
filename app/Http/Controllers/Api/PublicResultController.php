<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\ExamSession;
use App\Models\ExamResult;
use App\Http\Resources\ExamResultResource;
use Illuminate\Http\Request;

class PublicResultController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'center_slug' => 'required|string',
            'session_id' => 'required|integer',
            'table_number' => 'required|string|max:50',
        ]);

        // 1. Vérifier centre
        $center = Center::where('slug', $request->center_slug)->first();

        if (!$center) {
            return response()->json([
                'message' => 'Centre introuvable'
            ], 404);
        }

        // 2. Vérifier session
        $session = ExamSession::where('id', $request->session_id)
            ->where('center_id', $center->id)
            ->first();

        if (!$session) {
            return response()->json([
                'message' => 'Session introuvable'
            ], 404);
        }

        // 3. IMPORTANT : vérifier publication
        if (!$session->published) {
            return response()->json([
                'message' => 'Résultats pas encore publiés'
            ], 403);
        }

        // 4. Chercher résultat
        $result = ExamResult::with('examSession')
            ->where('exam_session_id', $session->id)
            ->where('table_number', $request->table_number)
            ->first();

        if (!$result) {
            return response()->json([
                'message' => 'Aucun résultat trouvé'
            ], 404);
        }

        // 5. Retour propre via Resource
        return new ExamResultResource($result);
    }
}