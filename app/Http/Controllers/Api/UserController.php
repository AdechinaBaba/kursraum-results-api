<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{

    public function createSecretary(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'center_id' => 'required|exists:centers,id',
        ]);

        $plainPassword = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'center_id' => $request->center_id,
            'role' => 'secretary',
            'password' => Hash::make($plainPassword),
            'must_change_password' => true,
        ]);

        return response()->json([
            'message' => 'Secretary created successfully',
            'user' => $user,
            'temporary_password' => $plainPassword,
        ], 201);
    }

    public function index()
    {
        $secretaries = User::with('center')
            ->where('role', 'secretary')
            ->get();

        return response()->json($secretaries);
    }

    public function show(User $user)
    {
        if ($user->role !== 'secretary') {
            return response()->json([
                'message' => 'User is not a secretary'
            ], 404);
        }
    
        return response()->json($user->load('center'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'secretary') {
            return response()->json([
                'message' => 'User is not a secretary'
            ], 404);
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'center_id' => 'required|exists:centers,id',
        ]);
    
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'center_id' => $request->center_id,
        ]);
    
        return response()->json([
            'message' => 'Secretary updated successfully',
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'secretary') {
            return response()->json([
                'message' => 'User is not a secretary'
            ], 404);
        }
    
        $user->delete();
    
        return response()->json([
            'message' => 'Secretary deleted successfully'
        ]);
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(10);
    
        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true
        ]);
    
        return response()->json([
            'message' => 'Password reset successfully',
            'temporary_password' => $newPassword
        ]);
    }
    
    

}
