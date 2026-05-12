<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role?->name !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::with('role')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role?->name,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
            ];
        });

        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'boolean',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $user->update($validated);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role?->name,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at,
        ]);
    }

    public function destroy($id)
    {
        if (Auth::id() == $id) {
            return response()->json(['message' => 'Cannot delete yourself'], 400);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
