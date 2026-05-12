<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KinshipController extends Controller
{
    public function find(Request $request)
    {
        $validated = $request->validate([
            'person_a_id' => ['required', 'uuid', 'exists:persons,id'],
            'person_b_id' => ['required', 'uuid', 'exists:persons,id'],
        ]);

        $personA = Person::find($validated['person_a_id']);
        $personB = Person::find($validated['person_b_id']);

        $kinship = $this->computeKinship($personA, $personB);

        return response()->json([
            'kinship' => $kinship,
        ]);
    }

    public function computeKinship(Person $personA, Person $personB)
    {
        if ($personA->id === $personB->id) {
            return [
                'is_same_person' => true,
                'term_a_to_b' => 'Bản thân',
                'term_b_to_a' => 'Bản thân',
                'path' => [],
                'distance' => 0,
            ];
        }

        $persons = Person::with(['relationshipsA', 'relationshipsB'])->get();
        $relationships = Relationship::all();

        $adjList = [];
        foreach ($relationships as $rel) {
            if (!isset($adjList[$rel->person_a])) {
                $adjList[$rel->person_a] = [];
            }
            if (!isset($adjList[$rel->person_b])) {
                $adjList[$rel->person_b] = [];
            }

            if ($rel->type === 'marriage') {
                $adjList[$rel->person_a][] = [
                    'id' => $rel->person_b,
                    'type' => 'spouse',
                    'rel_id' => $rel->id,
                ];
                $adjList[$rel->person_b][] = [
                    'id' => $rel->person_a,
                    'type' => 'spouse',
                    'rel_id' => $rel->id,
                ];
            } else {
                $adjList[$rel->person_a][] = [
                    'id' => $rel->person_b,
                    'type' => 'child',
                    'rel_id' => $rel->id,
                ];
                $adjList[$rel->person_b][] = [
                    'id' => $rel->person_a,
                    'type' => 'parent',
                    'rel_id' => $rel->id,
                ];
            }
        }

        $pathAtoB = $this->bfs($personA->id, $personB->id, $adjList);
        $pathBtoA = $this->bfs($personB->id, $personA->id, $adjList);

        if (!$pathAtoB && !$pathBtoA) {
            return [
                'is_same_person' => false,
                'is_related' => false,
                'term_a_to_b' => 'Không có quan hệ',
                'term_b_to_a' => 'Không có quan hệ',
                'path' => [],
                'distance' => -1,
            ];
        }

        $path = $pathAtoB ?: $pathBtoA;
        $isFromAtoB = (bool)$pathAtoB;

        $termAtoB = $this->getKinshipTerm($personA, $personB, $path, $isFromAtoB);
        $termBtoA = $this->getKinshipTerm($personB, $personA, array_reverse($path), !$isFromAtoB);

        return [
            'is_same_person' => false,
            'is_related' => true,
            'term_a_to_b' => $termAtoB,
            'term_b_to_a' => $termBtoA,
            'path' => $path,
            'distance' => count($path) - 1,
        ];
    }

    private function bfs($startId, $endId, $adjList)
    {
        if ($startId === $endId) {
            return [$startId];
        }

        $visited = [$startId];
        $queue = [[$startId]];

        while (!empty($queue)) {
            $path = array_shift($queue);
            $current = end($path);

            if (!isset($adjList[$current])) {
                continue;
            }

            foreach ($adjList[$current] as $neighbor) {
                $neighborId = $neighbor['id'];

                if ($neighborId === $endId) {
                    return array_merge($path, [$neighborId]);
                }

                if (!in_array($neighborId, $visited)) {
                    $visited[] = $neighborId;
                    $queue[] = array_merge($path, [$neighborId]);
                }
            }
        }

        return null;
    }

    private function getKinshipTerm(Person $from, Person $to, $path, $isFromAtoB)
    {
        $persons = Person::all()->keyBy('id');
        $pathPersons = [];
        foreach ($path as $pid) {
            $pathPersons[] = $persons[$pid] ?? null;
        }

        $path = array_filter($pathPersons);
        $path = array_values($path);

        if (count($path) < 2) {
            return 'Không xác định';
        }

        $start = $path[0];
        $end = $path[count($path) - 1];

        if (count($path) === 2) {
            $rel = Relationship::where(function ($q) use ($start, $end) {
                $q->where('person_a', $start->id)->where('person_b', $end->id);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('person_a', $end->id)->where('person_b', $start->id);
            })->first();

            if ($rel) {
                if ($rel->type === 'marriage') {
                    if ($start->gender === 'male') {
                        return 'Vợ';
                    } elseif ($start->gender === 'female') {
                        return 'Chồng';
                    }
                    return 'Vợ/Chồng';
                } elseif (in_array($rel->type, ['biological_child', 'adopted_child'])) {
                    if ($rel->person_a === $start->id) {
                        return $end->gender === 'male' ? 'Con trai' : 'Con gái';
                    } else {
                        return $end->gender === 'male' ? 'Cha' : 'Mẹ';
                    }
                }
            }
        }

        if (count($path) === 3) {
            $rel1 = Relationship::where('person_a', $start->id)->where('person_b', $path[1]->id)->first();
            $rel2 = Relationship::where('person_a', $path[1]->id)->where('person_b', $end->id)->first();

            if ($rel1 && $rel2) {
                if (in_array($rel1->type, ['biological_child', 'adopted_child']) &&
                    in_array($rel2->type, ['biological_child', 'adopted_child'])) {
                    return $end->gender === 'male' ? 'Anh/Em' : 'Chị/Em';
                }

                if ($rel1->type === 'marriage' && in_array($rel2->type, ['biological_child', 'adopted_child'])) {
                    return $end->gender === 'male' ? 'Con trai' : 'Con gái';
                }

                if (in_array($rel1->type, ['biological_child', 'adopted_child']) && $rel2->type === 'marriage') {
                    if ($start->gender === 'male') {
                        return 'Vợ của ' . ($end->gender === 'male' ? 'cha' : 'mẹ');
                    } else {
                        return 'Chồng của ' . ($end->gender === 'male' ? 'cha' : 'mẹ');
                    }
                }
            }
        }

        return 'Họ hàng (' . (count($path) - 1) . ' đời)';
    }
}
