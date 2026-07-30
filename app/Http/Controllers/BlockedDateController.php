<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Bike;
use Illuminate\Http\Request;

class BlockedDateController extends Controller
{
    public function index()
    {
        $bikes=Bike::whereHas('provision', function ($q)
        {
            $q->where('name', 'rent');
        })->orderBy('id')->get();
        return view('staff.blocked-dates.index', compact('bikes'));
    }

    public function events(Request $request)
    {
        $query = BlockedDate::query();

        if ($request->filled('bike_id')) {
            $query->forBike($request->bike_id);
        }

        $blocked = $query->get();

        return response()->json($blocked->map(fn($b) => [
            'id' => $b->id,
            'title' => $b->reason ?? 'Blocked',
            'start' => $b->start_date->format('Y-m-d'),
            'end' => $b->end_date->format('Y-m-d'),
        ]));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bike_id' => ['nullable', 'exists:bikes,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['created_by'] = auth()->id();

        $blocked = BlockedDate::create($validated);

        return response()->json($blocked, 201);
    }

    public function destroy(BlockedDate $blockedDate)
    {
        $blockedDate->delete();
        return response()->json(['deleted' => true]);
    }
}
