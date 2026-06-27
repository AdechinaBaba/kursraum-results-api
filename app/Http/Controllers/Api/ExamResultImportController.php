<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\ExamResultsImport;
use App\Models\ExamSession;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExamResultImportController extends Controller
{
    public function import(Request $request, ExamSession $session)
    {
        $this->authorize('import', $session);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(
            new ExamResultsImport($session, app(ResultService::class)),
            $request->file('file')
        );

        return response()->json([
            'message' => 'Import completed successfully'
        ]);
    }
}