<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marriage;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarriageController extends Controller
{
    public function store(Request $request, $familyId)
    {
        $family = Family::where('user_id', Auth::id())->findOrFail($familyId);

        $validated = $request->validate([
            'husband_id' => 'nullable|exists:people,id',
            'wife_id' => 'nullable|exists:people,id',
            'married_date' => 'nullable|date',
            'status' => 'required|in:married,divorced,widowed',
        ]);

        $validated['family_id'] = $familyId;

        $marriage = Marriage::create($validated);

        return response()->json($marriage, 201);
    }

    public function destroy($id)
    {
        $marriage = Marriage::findOrFail($id);
        
        // Ensure access
        Family::where('user_id', Auth::id())->findOrFail($marriage->family_id);
        
        $marriage->delete();

        return response()->json(null, 204);
    }
}
