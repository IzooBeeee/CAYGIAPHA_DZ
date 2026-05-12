<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Relationship;
use App\Models\CustomEvent;
use App\Models\PersonDetailsPrivate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataController extends Controller
{
    public function export()
    {
        $this->authorize('export', Person::class);

        $persons = Person::with(['privateDetails', 'customEvents'])->get();
        $relationships = Relationship::all();

        $personsData = $persons->map(function ($person) {
            return [
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
                'private_details' => $person->privateDetails ? [
                    'phone' => $person->privateDetails->phone,
                    'occupation' => $person->privateDetails->occupation,
                    'residence' => $person->privateDetails->residence,
                    'birthplace' => $person->privateDetails->birthplace,
                    'biography' => $person->privateDetails->biography,
                ] : null,
                'custom_events' => $person->customEvents->map(function ($event) {
                    return [
                        'title' => $event->title,
                        'event_year' => $event->event_year,
                        'event_month' => $event->event_month,
                        'event_day' => $event->event_day,
                        'is_lunar' => $event->is_lunar,
                        'description' => $event->description,
                    ];
                })->toArray(),
            ];
        });

        $relationshipsData = $relationships->map(function ($rel) {
            return [
                'type' => $rel->type,
                'person_a' => $rel->person_a,
                'person_b' => $rel->person_b,
                'note' => $rel->note,
            ];
        });

        $exportData = [
            'version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'persons' => $personsData,
            'relationships' => $relationshipsData,
        ];

        return response()->json($exportData);
    }

    public function import(Request $request)
    {
        $this->authorize('import', Person::class);

        $validated = $request->validate([
            'data' => ['required', 'array'],
            'mode' => ['in:replace,merge,update'],
        ]);

        $data = $validated['data'];
        $mode = $validated['mode'] ?? 'merge';

        if ($mode === 'replace') {
            Relationship::truncate();
            CustomEvent::truncate();
            PersonDetailsPrivate::truncate();
            Person::truncate();
        }

        DB::beginTransaction();

        try {
            $idMap = [];

            foreach ($data['persons'] as $personData) {
                $oldId = $personData['id'];
                unset($personData['id']);

                $privateDetails = $personData['private_details'] ?? null;
                unset($personData['private_details']);

                $customEvents = $personData['custom_events'] ?? [];
                unset($personData['custom_events']);

                $existingPerson = null;
                if ($mode === 'update' || $mode === 'merge') {
                    $existingPerson = Person::where('full_name', $personData['full_name'])
                        ->where('gender', $personData['gender'])
                        ->first();
                }

                if ($existingPerson) {
                    $person = $existingPerson;
                } else {
                    $person = Person::create($personData);
                }

                $idMap[$oldId] = $person->id;

                if ($privateDetails && $request->user()->isAdmin()) {
                    PersonDetailsPrivate::updateOrCreate(
                        ['person_id' => $person->id],
                        $privateDetails
                    );
                }

                foreach ($customEvents as $eventData) {
                    CustomEvent::create([
                        'person_id' => $person->id,
                        'title' => $eventData['title'],
                        'event_year' => $eventData['event_year'] ?? null,
                        'event_month' => $eventData['event_month'],
                        'event_day' => $eventData['event_day'],
                        'is_lunar' => $eventData['is_lunar'] ?? false,
                        'description' => $eventData['description'] ?? null,
                    ]);
                }
            }

            foreach ($data['relationships'] ?? [] as $relData) {
                $personAId = $idMap[$relData['person_a']] ?? null;
                $personBId = $idMap[$relData['person_b']] ?? null;

                if (!$personAId || !$personBId) {
                    continue;
                }

                $existing = Relationship::where('person_a', $personAId)
                    ->where('person_b', $personBId)
                    ->first();

                if (!$existing) {
                    Relationship::create([
                        'type' => $relData['type'],
                        'person_a' => $personAId,
                        'person_b' => $personBId,
                        'note' => $relData['note'] ?? null,
                    ]);

                    if ($relData['type'] === 'marriage') {
                        Relationship::create([
                            'type' => 'marriage',
                            'person_a' => $personBId,
                            'person_b' => $personAId,
                            'note' => $relData['note'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Nhập dữ liệu thành công',
                'imported' => [
                    'persons' => count($data['persons']),
                    'relationships' => count($data['relationships'] ?? []),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Lỗi khi nhập dữ liệu: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function exportCsv()
    {
        $this->authorize('export', Person::class);

        $persons = Person::all();

        $csv = "ID,Họ tên,Giới tính,Năm sinh,Tháng sinh,Ngày sinh,Ngày âm lịch,Năm mất,Tháng mất,Ngày mất,Âm lịch mất,Đã mất,Thế hệ,Thứ tự,Người nhà,Ngày tạo\n";

        foreach ($persons as $person) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $person->id,
                $person->full_name,
                $person->gender,
                $person->birth_year ?? '',
                $person->birth_month ?? '',
                $person->birth_day ?? '',
                $person->birth_is_lunar ? 'Có' : 'Không',
                $person->death_year ?? '',
                $person->death_month ?? '',
                $person->death_day ?? '',
                $person->death_is_lunar ? 'Có' : 'Không',
                $person->is_deceased ? 'Có' : 'Không',
                $person->generation ?? '',
                $person->birth_order ?? '',
                $person->is_in_law ? 'Có' : 'Không',
                $person->created_at
            );
        }

        $filename = 'giapha_export_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($csv) {
            echo "\xEF\xBB\xBF";
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
