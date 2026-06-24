<?php

namespace App\Http\Controllers\Api;

use App\Models\ExamResult;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExamResult\StoreExamResultRequest;
use App\Http\Resources\ExamResultResource;
use App\Services\ResultService;

class ExamResultController extends Controller
{
    public function __construct(
        private ResultService $resultService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', ExamResult::class);

        $results = ExamResult::with('examSession')->latest()->get();

        return ExamResultResource::collection($results);
    }

    public function store(StoreExamResultRequest $request)
    {
        $this->authorize('create', ExamResult::class);

        $result = ExamResult::create($request->validated());

        return new ExamResultResource($result->load('examSession'));
    }

    public function show(ExamResult $examResult)
    {
        $this->authorize('view', $examResult);

        return new ExamResultResource($examResult->load('examSession'));
    }

    public function update(StoreExamResultRequest $request, ExamResult $examResult)
    {
        $this->authorize('update', $examResult);

        $examResult->update($request->validated());

        return new ExamResultResource($examResult->load('examSession'));
    }

    public function destroy(ExamResult $examResult)
    {
        $this->authorize('delete', $examResult);

        $examResult->delete();

        return response()->json([
            'message' => 'Result deleted successfully'
        ]);
    }
}