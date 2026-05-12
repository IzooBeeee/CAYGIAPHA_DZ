<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\PersonDetailsPrivate;
use App\Models\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::with(['relationshipsA', 'relationshipsB']);

        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('is_in_law')) {
            $query->where('is_in_law', $request->boolean('is_in_law'));
        }

        if ($request->has('is_deceased')) {
            $query->where('is_deceased', $request->boolean('is_deceased'));
        }

        if ($request->has('generation')) {
            $query->where('generation', $request->generation);
        }

        $persons = $query->orderBy('generation')->orderBy('birth_order')->get();

        return response()->json([
            'persons' => $persons,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Person::class);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'birth_year' => ['nullable', 'integer', 'min:1800', 'max:2100'],
            'birth_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'birth_is_lunar' => ['boolean'],
            'death_year' => ['nullable', 'integer', 'min:1800', 'max:2100'],
            'death_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'death_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'death_is_lunar' => ['boolean'],
            'is_deceased' => ['boolean'],
            'avatar_url' => ['nullable', 'string', 'url'],
            'note' => ['nullable', 'string'],
            'other_names' => ['nullable', 'string'],
            'generation' => ['nullable', 'integer', 'min:1'],
            'birth_order' => ['nullable', 'integer', 'min:1'],
            'is_in_law' => ['boolean'],
        ]);

        if (isset($validated['is_deceased']) && $validated['is_deceased'] && isset($validated['death_year'])) {
            $validated['is_deceased'] = true;
        } else {
            $validated['is_deceased'] = false;
        }

        $person = Person::create($validated);

        return response()->json([
            'message' => 'Thêm thành viên thành công',
            'person' => $person,
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $person = Person::with([
            'relationshipsA.personB',
            'relationshipsB.personA',
            'customEvents',
            'privateDetails',
        ])->findOrFail($id);

        $this->authorize('view', $person);

        $relationships = $this->formatRelationships($person);

        $data = [
            'id' => $person->id,
            'full_name' => $person->full_name,
            'gender' => $person->gender,
            'birth_year' => $person->birth_year,
            'birth_month' => $person->birth_month,
            'birth_day' => $person->birth_day,
            'birth_is_lunar' => $person->birth_is_lunar,
            'death_year' => $person->death_year,
            'death_month' => $person->death_month,
            'death_day' => $person->death_day,
            'death_is_lunar' => $person->death_is_lunar,
            'is_deceased' => $person->is_deceased,
            'avatar_url' => $person->avatar_url,
            'note' => $person->note,
            'other_names' => $person->other_names,
            'generation' => $person->generation,
            'birth_order' => $person->birth_order,
            'is_in_law' => $person->is_in_law,
            'relationships' => $relationships,
            'custom_events' => $person->customEvents,
        ];

        if ($request->user()->isAdmin()) {
            $data['private_details'] = $person->privateDetails;
        }

        return response()->json(['person' => $data]);
    }

    public function update(Request $request, string $id)
    {
        $person = Person::findOrFail($id);
        $this->authorize('update', $person);

        $validated = $request->validate([
            'full_name' => ['sometimes', 'string', 'max:255'],
            'gender' => ['sometimes', 'in:male,female,other'],
            'birth_year' => ['nullable', 'integer', 'min:1800', 'max:2100'],
            'birth_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'birth_is_lunar' => ['boolean'],
            'death_year' => ['nullable', 'integer', 'min:1800', 'max:2100'],
            'death_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'death_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'death_is_lunar' => ['boolean'],
            'is_deceased' => ['boolean'],
            'avatar_url' => ['nullable', 'string', 'url'],
            'note' => ['nullable', 'string'],
            'other_names' => ['nullable', 'string'],
            'generation' => ['nullable', 'integer', 'min:1'],
            'birth_order' => ['nullable', 'integer', 'min:1'],
            'is_in_law' => ['boolean'],
        ]);

        $person->update($validated);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'person' => $person,
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $person = Person::findOrFail($id);
        $this->authorize('delete', $person);

        $person->delete();

        return response()->json([
            'message' => 'Xóa thành viên thành công',
        ]);
    }

    public function tree(Request $request)
    {
        $rootId = $request->get('root_id');

        $persons = Person::with(['relationshipsA', 'relationshipsB'])->get();
        $relationships = Relationship::all();

        $treeData = $this->buildTreeData($persons, $relationships, $rootId);

        return response()->json([
            'persons' => $persons,
            'relationships' => $relationships,
            'tree' => $treeData,
        ]);
    }

    public function updatePrivateDetails(Request $request, string $id)
    {
        $person = Person::findOrFail($id);
        $this->authorize('updatePrivateDetails', $person);

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'residence' => ['nullable', 'string', 'max:255'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ]);

        $person->privateDetails()->updateOrCreate(
            ['person_id' => $person->id],
            $validated
        );

        return response()->json([
            'message' => 'Cập nhật thông tin riêng tư thành công',
            'private_details' => $person->fresh()->privateDetails,
        ]);
    }

    private function formatRelationships(Person $person)
    {
        $relationships = [];

        foreach ($person->relationshipsA as $rel) {
            if ($rel->type === 'marriage') {
                $relationships['spouses'][] = [
                    'id' => $rel->personB->id,
                    'full_name' => $rel->personB->full_name,
                    'gender' => $rel->personB->gender,
                    'note' => $rel->note,
                ];
            } else {
                $relationships['children'][] = [
                    'id' => $rel->personB->id,
                    'full_name' => $rel->personB->full_name,
                    'gender' => $rel->personB->gender,
                    'type' => $rel->type,
                    'note' => $rel->note,
                ];
            }
        }

        foreach ($person->relationshipsB as $rel) {
            if (in_array($rel->type, ['biological_child', 'adopted_child'])) {
                $relationships['parents'][] = [
                    'id' => $rel->personA->id,
                    'full_name' => $rel->personA->full_name,
                    'gender' => $rel->personA->gender,
                    'type' => $rel->type,
                    'note' => $rel->note,
                ];
            }
        }

        return $relationships;
    }

    private function buildTreeData($persons, $relationships, $rootId = null)
    {
        $personsMap = $persons->keyBy('id');
        $adjList = [];

        foreach ($relationships as $rel) {
            if ($rel->type === 'marriage' || in_array($rel->type, ['biological_child', 'adopted_child'])) {
                if (!isset($adjList[$rel->person_a])) {
                    $adjList[$rel->person_a] = ['spouses' => [], 'children' => []];
                }
                if (!isset($adjList[$rel->person_b])) {
                    $adjList[$rel->person_b] = ['spouses' => [], 'children' => []];
                }

                if ($rel->type === 'marriage') {
                    $adjList[$rel->person_a]['spouses'][] = $rel->person_b;
                    $adjList[$rel->person_b]['spouses'][] = $rel->person_a;
                } else {
                    $adjList[$rel->person_a]['children'][] = $rel->person_b;
                }
            }
        }

        return [
            'adjacency' => $adjList,
        ];
    }
}
