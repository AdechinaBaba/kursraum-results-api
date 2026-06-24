<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Center;
use App\Models\ExamSession;

class ExamSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $query = ExamSession::with('center');

        if ($user->role === 'secretary') {
            $query->where('center_id', $user->center_id);
        }

        return response()->json(
            $query->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'center_id' => 'nullable|exists:centers,id',
            'title' => 'required|string|max:255',
            'level' => 'required|in:A1,A2,B1,B2,C1,C2',
            'module_max_score' => 'required|integer|min:1',
            'passing_percentage' => 'required|numeric|min:1|max:100',
            'exam_date' => 'required|date',
        ]);
    
        $user = auth()->user();
    
        $centerId = $user->role === 'super_admin'
            ? $request->center_id
            : $user->center_id;
    
        $session = ExamSession::create([
            'center_id' => $centerId,
            'title' => $request->title,
            'level' => $request->level,
            'module_max_score' => $request->module_max_score,
            'passing_percentage' => $request->passing_percentage,
            'exam_date' => $request->exam_date,
            'published' => false,
        ]);
    
        return response()->json([
            'message' => 'Exam session created successfully.',
            'session' => $session,
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(ExamSession $examSession)
    {
        $user = auth()->user();

        if (
            $user->role === 'secretary' &&
            $examSession->center_id !== $user->center_id
        ) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        return response()->json(
            $examSession->load('center')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamSession $examSession)
    {
        $user = auth()->user();

        if (
            $user->role === 'secretary' &&
            $examSession->center_id !== $user->center_id
        ) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:A1,A2,B1,B2,C1,C2',
            'module_max_score' => 'required|integer|min:1',
            'passing_percentage' => 'required|numeric|min:1|max:100',
            'exam_date' => 'required|date',
        ]);

        $examSession->update([
            'title' => $request->title,
            'description' => $request->description,
            'level' => $request->level,
            'module_max_score' => $request->module_max_score,
            'passing_percentage' => $request->passing_percentage,
            'exam_date' => $request->exam_date,
        ]);

        return response()->json([
            'message' => 'Exam session updated successfully.',
            'session' => $examSession,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamSession $examSession)
    {
        $user = auth()->user();

        if (
            $user->role === 'secretary' &&
            $examSession->center_id !== $user->center_id
        ) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        if ($examSession->results()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer une session contenant déjà des résultats.'
            ], 422);
        }

        $examSession->delete();

        return response()->json([
            'message' => 'Exam session deleted successfully.'
        ]);
    }


    public function togglePublication(ExamSession $examSession)
    {
        $user = auth()->user();

        if (
            $user->role === 'secretary' &&
            $examSession->center_id !== $user->center_id
        ) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $examSession->update([
            'published' => ! $examSession->published,
        ]);

        return response()->json([
            'message' => $examSession->published
                ? 'Results published successfully.'
                : 'Results unpublished successfully.',
            'published' => $examSession->published,
        ]);
    }
}
