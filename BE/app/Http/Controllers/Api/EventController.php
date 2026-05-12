<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomEvent;
use App\Models\Person;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $upcoming = $request->boolean('upcoming', false);
        $days = $request->get('days', 30);

        $query = CustomEvent::with('person')->orderBy('event_month')->orderBy('event_day');

        if ($upcoming) {
            $currentMonth = now()->month;
            $currentDay = now()->day;
            $currentYear = now()->year;

            $query->where(function ($q) use ($currentMonth, $currentDay, $currentYear, $days) {
                $q->whereRaw("CASE
                    WHEN is_lunar = 0 THEN
                        (event_year IS NULL AND (
                            (event_month > {$currentMonth}) OR
                            (event_month = {$currentMonth} AND event_day >= {$currentDay})
                        ))
                    ELSE
                        (event_year IS NULL AND (
                            (event_month > {$currentMonth}) OR
                            (event_month = {$currentMonth} AND event_day >= {$currentDay})
                        ))
                END")
                ->orWhereRaw("event_year IS NOT NULL");
            });
        }

        $events = $query->get();

        return response()->json([
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', CustomEvent::class);

        $validated = $request->validate([
            'person_id' => ['required', 'uuid', 'exists:persons,id'],
            'title' => ['required', 'string', 'max:255'],
            'event_year' => ['nullable', 'integer', 'min:1800', 'max:2100'],
            'event_month' => ['required', 'integer', 'min:1', 'max:12'],
            'event_day' => ['required', 'integer', 'min:1', 'max:31'],
            'is_lunar' => ['boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $event = CustomEvent::create($validated);

        return response()->json([
            'message' => 'Thêm sự kiện thành công',
            'event' => $event->load('person'),
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $event = CustomEvent::findOrFail($id);
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'event_year' => ['nullable', 'integer', 'min:1800', 'max:2100'],
            'event_month' => ['sometimes', 'integer', 'min:1', 'max:12'],
            'event_day' => ['sometimes', 'integer', 'min:1', 'max:31'],
            'is_lunar' => ['boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $event->update($validated);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'event' => $event->load('person'),
        ]);
    }

    public function destroy(string $id)
    {
        $event = CustomEvent::findOrFail($id);
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json([
            'message' => 'Xóa sự kiện thành công',
        ]);
    }
}
