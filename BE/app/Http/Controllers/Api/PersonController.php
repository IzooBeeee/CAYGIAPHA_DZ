<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    public function index($familyId)
    {
        $family = Family::where('user_id', Auth::id())->findOrFail($familyId);
        $people = Person::where('family_id', $familyId)->get();
        return response()->json($people);
    }

    public function store(Request $request, $familyId)
    {
        $family = Family::where('user_id', Auth::id())->findOrFail($familyId);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'avatar' => 'nullable|string',
            'biography' => 'nullable|string',
            'father_id' => 'nullable|exists:people,id',
            'mother_id' => 'nullable|exists:people,id',
        ]);

        $validated['family_id'] = $familyId;

        $person = Person::create($validated);

        return response()->json($person, 201);
    }

    public function show($id)
    {
        $person = Person::with(['father', 'mother'])->findOrFail($id);
        
        // Ensure user has access to this person's family
        Family::where('user_id', Auth::id())->findOrFail($person->family_id);

        return response()->json($person);
    }

    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        
        // Ensure user has access
        Family::where('user_id', Auth::id())->findOrFail($person->family_id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'avatar' => 'nullable|string',
            'biography' => 'nullable|string',
            'father_id' => 'nullable|exists:people,id',
            'mother_id' => 'nullable|exists:people,id',
        ]);

        $person->update($validated);

        return response()->json($person);
    }

    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        
        // Ensure user has access
        Family::where('user_id', Auth::id())->findOrFail($person->family_id);
        
        $person->delete();

        return response()->json(null, 204);
    }
}
