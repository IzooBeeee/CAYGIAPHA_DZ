<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Family;
use Illuminate\Support\Facades\Auth;

class TreeController extends Controller
{
    public function show($familyId)
    {
        // Hỗ trợ "auto" → tự tìm gia phả đầu tiên của user
        if ($familyId === 'auto') {
            $family = Family::where('user_id', Auth::id())->first();
            if (!$family) {
                return response()->json([
                    'message' => 'Không tìm thấy gia phả hoặc bạn không có quyền truy cập'
                ], 403);
            }
            $familyId = $family->id;
        } else {
            $family = Family::where('user_id', Auth::id())->find($familyId);
            if (!$family) {
                return response()->json([
                    'message' => 'Không tìm thấy gia phả hoặc bạn không có quyền truy cập'
                ], 403);
            }
        }

        // Lấy tất cả người trong gia phả
        $allPeople = Person::where('family_id', $familyId)->get()->keyBy('id');

        // Tìm các "root" thực sự: những người không có cha/mẹ TRONG gia phả này
        // Ưu tiên: người nam không có cha mẹ = tổ tiên gốc
        $peopleIds = $allPeople->keys()->toArray();

        $roots = $allPeople->filter(function ($person) use ($peopleIds) {
            $hasParentInFamily = (
                ($person->father_id && in_array($person->father_id, $peopleIds)) ||
                ($person->mother_id && in_array($person->mother_id, $peopleIds))
            );
            return !$hasParentInFamily && $person->gender === 'male';
        });

        // Nếu không có root nam, lấy tất cả người không có cha mẹ
        if ($roots->isEmpty()) {
            $roots = $allPeople->filter(function ($person) use ($peopleIds) {
                return !(
                    ($person->father_id && in_array($person->father_id, $peopleIds)) ||
                    ($person->mother_id && in_array($person->mother_id, $peopleIds))
                );
            });
        }

        // Lấy người gốc đầu tiên (cụ tổ)
        $root = $roots->sortBy('birth_date')->first();

        if (!$root) {
            return response()->json(['message' => 'Không tìm thấy người gốc trong cây gia phả'], 404);
        }

        // Build cây với cache tránh loop vô hạn
        $visited = [];
        $tree = $this->buildTree($root, $allPeople, $visited);

        return response()->json([
            'family'     => [
                'id'          => $family->id,
                'name'        => $family->name,
                'description' => $family->description,
            ],
            'tree' => $tree,
            'total_members' => $allPeople->count(),
        ]);
    }

    private function buildTree(Person $person, $allPeople, array &$visited): array
    {
        if (in_array($person->id, $visited)) {
            return [];
        }
        $visited[] = $person->id;

        // spouses() trả về Collection trực tiếp (không phải Builder)
        $spouses = $person->spouses()->map(fn($s) => [
            'id'         => $s->id,
            'full_name'  => $s->full_name,
            'gender'     => $s->gender,
            'birth_date' => $s->birth_date?->format('Y'),
            'death_date' => $s->death_date?->format('Y'),
            'avatar'     => $s->avatar,
        ])->values();

        // Lấy con của người này (qua father_id hoặc mother_id)
        $childrenIds = $allPeople
            ->filter(function ($p) use ($person) {
                return $p->father_id === $person->id || $p->mother_id === $person->id;
            })
            ->keys()
            ->toArray();

        // Chỉ lấy con chưa được visit và là con "chính" (bloodline: có gender cụ thể)
        // Lọc: loại bỏ những người đã được xác định là vợ/chồng (trong marriages)
        $spouseIds = $spouses->pluck('id')->toArray();
        $spouseIds[] = $person->id;

        $children = collect($childrenIds)
            ->filter(fn($id) => !in_array($id, $visited))
            ->map(fn($id) => $allPeople->get($id))
            ->filter()
            ->sortBy('birth_date')
            ->map(fn($child) => $this->buildTree($child, $allPeople, $visited))
            ->filter(fn($c) => !empty($c))
            ->values();

        return [
            'id'         => $person->id,
            'full_name'  => $person->full_name,
            'gender'     => $person->gender,
            'birth_date' => $person->birth_date?->format('Y'),
            'death_date' => $person->death_date?->format('Y'),
            'birth_place' => $person->birth_place,
            'biography'  => $person->biography,
            'avatar'     => $person->avatar,
            'spouses'    => $spouses,
            'children'   => $children,
        ];
    }
}
