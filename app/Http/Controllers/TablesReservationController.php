<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Tables;
use Illuminate\Http\Request;
use Laravel\Prompts\Table;

class TablesReservationController extends Controller
{

    private function AvailableTables($date, $time, $numPeople)
{
    return Tables::where('capacity', '>=', $numPeople)
        ->whereDoesntHave('reservations', function ($query) use ($date, $time) {
            $query->where('date', $date) // Ensure it's the same day
                  ->whereRaw("
                      (TIME(?) BETWEEN reservations.hour AND ADDTIME(reservations.hour, '02:00:00'))
                      OR (reservations.hour BETWEEN TIME(?) AND ADDTIME(?, '02:00:00'))
                  ", [$time, $time, $time]);
        })
        ->orderBy('capacity', 'asc') // Pick the smallest available table
        ->first();
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //return  dd(Tables::first()->reservations()->getQuery()->toSql());
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:255',
            'date' => 'required|date',
            'hour' => 'required|date_format:H:i',
            'number_of_persones' => 'required|integer|min:1',
        ]);

        $table = $this->AvailableTables($validated['date'], $validated['hour'], $validated['number_of_persones']);
        if (!$table) {
            return response()->json(['error' => 'No available tables'], 400);
        }

        $reservation = Reservation::create([
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'],
            'tables_id' => $table->id,
            'date' => $validated['date'],
            'hour' => $validated['hour'],
            'number_of_persones' => $validated['number_of_persones'],
        ]);

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation,
        ], 201);

    }

    


    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
