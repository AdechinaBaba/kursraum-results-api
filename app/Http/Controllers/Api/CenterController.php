<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Center;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centers = Center::withCount([
            'users',
            'examSessions'
        ])->latest()->get();

        return response()->json($centers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:centers,slug',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $center = Center::create([
            'name' => $request->name,
            'slug' => strtolower($request->slug),
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => true,
        ]);

        return response()->json([
            'message' => 'Center created successfully',
            'center' => $center
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json($center);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Center $center)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:centers,slug,' . $center->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $center->update([
            'name' => $request->name,
            'slug' => strtolower($request->slug),
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'message' => 'Center updated successfully',
            'center' => $center
        ]);
    }

    public function toggleStatus(Center $center)
    {
        $center->update([
            'status' => !$center->status
        ]);

        return response()->json([
            'message' => 'Center status updated',
            'status' => $center->status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Center $center)
    {
        if ($center->users()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete center with assigned secretaries'
            ], 422);
        }

        $center->delete();

        return response()->json([
            'message' => 'Center deleted successfully'
        ]);
    }
}
