<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Relationship;
use App\Models\CustomEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $totalPersons = Person::count();
        $totalMales = Person::where('gender', 'male')->count();
        $totalFemales = Person::where('gender', 'female')->count();
        $totalDeceased = Person::where('is_deceased', true)->count();
        $totalAlive = $totalPersons - $totalDeceased;
        $totalGenerations = Person::max('generation') ?? 0;

        $genderDistribution = [
            ['label' => 'Nam', 'value' => $totalMales],
            ['label' => 'Nữ', 'value' => $totalFemales],
        ];

        $generationDistribution = Person::whereNotNull('generation')
            ->selectRaw('generation as label, COUNT(*) as value')
            ->groupBy('generation')
            ->orderBy('generation')
            ->get();

        $ageStats = $this->calculateAgeStats();

        $familySizeStats = $this->calculateFamilySizeStats();

        $upcomingEvents = $this->getUpcomingEvents();

        return response()->json([
            'stats' => [
                'total_persons' => $totalPersons,
                'total_males' => $totalMales,
                'total_females' => $totalFemales,
                'total_deceased' => $totalDeceased,
                'total_alive' => $totalAlive,
                'total_generations' => $totalGenerations,
                'gender_distribution' => $genderDistribution,
                'generation_distribution' => $generationDistribution,
                'age_stats' => $ageStats,
                'family_size_stats' => $familySizeStats,
                'upcoming_events' => $upcomingEvents,
            ],
        ]);
    }

    private function calculateAgeStats()
    {
        $alivePersons = Person::where('is_deceased', false)
            ->whereNotNull('birth_year')
            ->get();

        $ages = $alivePersons->map(function ($person) {
            return now()->year - $person->birth_year;
        })->filter();

        if ($ages->isEmpty()) {
            return [
                'min' => 0,
                'max' => 0,
                'average' => 0,
            ];
        }

        return [
            'min' => $ages->min(),
            'max' => $ages->max(),
            'average' => round($ages->avg(), 1),
            'count' => $ages->count(),
        ];
    }

    private function calculateFamilySizeStats()
    {
        $persons = Person::all();
        $spouseCounts = [];

        foreach ($persons as $person) {
            $spouseCount = Relationship::where('type', 'marriage')
                ->where(function ($q) use ($person) {
                    $q->where('person_a', $person->id)->orWhere('person_b', $person->id);
                })
                ->count();

            if (!isset($spouseCounts[$spouseCount])) {
                $spouseCounts[$spouseCount] = 0;
            }
            $spouseCounts[$spouseCount]++;
        }

        $stats = [];
        ksort($spouseCounts);
        foreach ($spouseCounts as $count => $freq) {
            $label = $count === 0 ? 'Độc thân' : ($count === 1 ? 'Một vợ/chồng' : "{$count} vợ/chồng");
            $stats[] = ['label' => $label, 'value' => $freq];
        }

        return $stats;
    }

    private function getUpcomingEvents($days = 30)
    {
        $currentMonth = now()->month;
        $currentDay = now()->day;

        $events = [];

        $birthdays = Person::where('is_deceased', false)
            ->whereNotNull('birth_year')
            ->whereNotNull('birth_month')
            ->whereNotNull('birth_day')
            ->get()
            ->filter(function ($person) use ($currentMonth, $currentDay, $days) {
                $diff = $this->daysUntil($person->birth_month, $person->birth_day, $currentMonth, $currentDay);
                return $diff >= 0 && $diff <= $days;
            })
            ->map(function ($person) use ($currentMonth, $currentDay) {
                $age = now()->year - $person->birth_year;
                return [
                    'type' => 'birthday',
                    'person_id' => $person->id,
                    'person_name' => $person->full_name,
                    'date' => $person->birth_day . '/' . $person->birth_month . '/' . now()->year,
                    'is_lunar' => $person->birth_is_lunar,
                    'days_until' => $this->daysUntil($person->birth_month, $person->birth_day, $currentMonth, $currentDay),
                    'age' => $age,
                ];
            })
            ->sortBy('days_until')
            ->take(5)
            ->values();

        $deathAnniversaries = Person::where('is_deceased', true)
            ->whereNotNull('death_year')
            ->whereNotNull('death_month')
            ->whereNotNull('death_day')
            ->get()
            ->filter(function ($person) use ($currentMonth, $currentDay, $days) {
                $diff = $this->daysUntil($person->death_month, $person->death_day, $currentMonth, $currentDay);
                return $diff >= 0 && $diff <= $days;
            })
            ->map(function ($person) use ($currentMonth, $currentDay) {
                return [
                    'type' => 'death_anniversary',
                    'person_id' => $person->id,
                    'person_name' => $person->full_name,
                    'date' => $person->death_day . '/' . $person->death_month . '/' . (now()->year + 1),
                    'is_lunar' => $person->death_is_lunar,
                    'days_until' => $this->daysUntil($person->death_month, $person->death_day, $currentMonth, $currentDay),
                    'death_year' => $person->death_year,
                ];
            })
            ->sortBy('days_until')
            ->take(5)
            ->values();

        $customEvents = CustomEvent::whereNull('event_year')
            ->get()
            ->filter(function ($event) use ($currentMonth, $currentDay, $days) {
                $diff = $this->daysUntil($event->event_month, $event->event_day, $currentMonth, $currentDay);
                return $diff >= 0 && $diff <= $days;
            })
            ->map(function ($event) use ($currentMonth, $currentDay) {
                return [
                    'type' => 'custom',
                    'event_id' => $event->id,
                    'title' => $event->title,
                    'person_id' => $event->person_id,
                    'person_name' => $event->person->full_name ?? null,
                    'date' => $event->event_day . '/' . $event->event_month . '/' . now()->year,
                    'is_lunar' => $event->is_lunar,
                    'days_until' => $this->daysUntil($event->event_month, $event->event_day, $currentMonth, $currentDay),
                ];
            })
            ->sortBy('days_until')
            ->take(5)
            ->values();

        return $birthdays->merge($deathAnniversaries)->merge($customEvents)->sortBy('days_until')->take(10)->values();
    }

    private function daysUntil($targetMonth, $targetDay, $currentMonth, $currentDay)
    {
        $currentDate = $currentMonth * 100 + $currentDay;
        $targetDate = $targetMonth * 100 + $targetDay;

        if ($targetDate >= $currentDate) {
            return $targetDate - $currentDate;
        }

        return (1231 - $currentDate) + $targetDate;
    }
}
