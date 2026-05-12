<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Marriage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
    public function index()
    {
        $families = Family::where('user_id', Auth::id())->get();
        return response()->json($families);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        $family = Family::create($validated);

        return response()->json($family, 201);
    }

    public function show($id)
    {
        $family = Family::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($family);
    }

    public function update(Request $request, $id)
    {
        $family = Family::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $family->update($validated);

        return response()->json($family);
    }

    public function destroy($id)
    {
        $family = Family::where('user_id', Auth::id())->findOrFail($id);
        $family->delete();

        return response()->json(null, 204);
    }

    public function marriages($familyId)
    {
        $family = Family::where('user_id', Auth::id())->findOrFail($familyId);
        $marriages = Marriage::where('family_id', $familyId)
            ->with(['husband', 'wife'])
            ->get();
        return response()->json($marriages);
    }
}
