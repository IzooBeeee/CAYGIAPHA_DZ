<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Relationship;
use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    public function index(Request $request)
    {
        $personId = $request->get('person_id');

        $query = Relationship::with(['personA', 'personB']);

        if ($personId) {
            $query->where('person_a', $personId)->orWhere('person_b', $personId);
        }

        $relationships = $query->get();

        return response()->json([
            'relationships' => $relationships,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Relationship::class);

        $validated = $request->validate([
            'type' => ['required', 'in:marriage,biological_child,adopted_child'],
            'person_a' => ['required', 'uuid', 'exists:persons,id'],
            'person_b' => ['required', 'uuid', 'exists:persons,id'],
            'note' => ['nullable', 'string'],
        ]);

        $existing = Relationship::where('person_a', $validated['person_a'])
            ->where('person_b', $validated['person_b'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Mối quan hệ đã tồn tại',
            ], 400);
        }

        $relationship = Relationship::create($validated);

        if ($validated['type'] === 'marriage') {
            Relationship::create([
                'type' => 'marriage',
                'person_a' => $validated['person_b'],
                'person_b' => $validated['person_a'],
                'note' => $validated['note'] ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Thêm mối quan hệ thành công',
            'relationship' => $relationship->load(['personA', 'personB']),
        ], 201);
    }

    public function show(string $id)
    {
        $relationship = Relationship::with(['personA', 'personB'])->findOrFail($id);

        return response()->json([
            'relationship' => $relationship,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $relationship = Relationship::findOrFail($id);
        $this->authorize('update', $relationship);

        $validated = $request->validate([
            'note' => ['nullable', 'string'],
        ]);

        $relationship->update($validated);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'relationship' => $relationship,
        ]);
    }

    public function destroy(string $id)
    {
        $relationship = Relationship::findOrFail($id);
        $this->authorize('delete', $relationship);

        Relationship::where('person_a', $relationship->person_b)
            ->where('person_b', $relationship->person_a)
            ->where('type', $relationship->type)
            ->delete();

        $relationship->delete();

        return response()->json([
            'message' => 'Xóa mối quan hệ thành công',
        ]);
    }
}
