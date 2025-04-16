<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Tables;
use Illuminate\Http\Request;
use Laravel\Prompts\Table;

class TablesReservationController extends Controller
{

   
    

    private function AvailableTables($date, $time, $numPeople, $durationHours )
   {
        $startDateTime = "$date $time";

        // Do the duration math in PHP
         $durationInSeconds = $durationHours * 3600;
         $endDateTime = date('Y-m-d H:i:s', strtotime($startDateTime) + $durationInSeconds);

        return Tables::where('capacity', '>=', $numPeople)
            ->whereDoesntHave('reservations', function ($query) use ($startDateTime, $endDateTime) {
              $query->whereRaw("
                 (
                     ? < ADDTIME(CONCAT(reservations.date, ' ', reservations.hour), SEC_TO_TIME(reservations.duration * 3600)) 
                       AND
                       ? > CONCAT(reservations.date, ' ', reservations.hour)
                       AND reservations.status != 'canceled'
                 )
                ", [$startDateTime, $endDateTime]);
            })
            ->orderBy('capacity', 'asc')
            ->first();
    }

    

    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reservation::with('table');
    
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
    
        if ($request->has('date')) {
            $query->whereDate('date', $request->input('date'));
        }
    
        $reservations = $query->get();
    
        return response()->json([
            'reservations' => $reservations,
        ], 200);
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
            'duration' => 'required|numeric', // Duration in hours
        ]);

        $table = $this->AvailableTables(
            $validated['date'], $validated['hour'],
             $validated['number_of_persones'] ,
             $validated['duration']
            );

      
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
            'duration' => $validated['duration'],
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
        return response()->json([
            'reservation' => $reservation,
        ] , 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:255',
            'date' => 'required|date',
            'hour' => 'required|date_format:H:i',
            'number_of_persones' => 'required|integer|min:1',
            'duration' => 'required|numeric', // Duration in hours
        ]);

        

            $table = null;
            $table = $this->AvailableTables($validated['date'], $validated['hour'], $validated['number_of_persones'] ,$validated['duration']);
            if (!$table) {
                if($validated['number_of_persones'] > $reservation->table->capacity){
                    return response()->json(['error' => 'No available tables'], 400);
                }
                $table = $reservation->table;
            }
        

        $reservation->update(
            [
                'client_name' => $validated['client_name'],
                'client_phone' => $validated['client_phone'],
                'tables_id' => $table->id,
                'date' => $validated['date'],
                'hour' => $validated['hour'],
                'number_of_persones' => $validated['number_of_persones'],
                'duration' => $validated['duration'],
                'status' => $validated['status'] ?? $reservation->status,
            ]
        );

        return response()->json([
            'message' => 'Reservation updated successfully',
            'reservation' => $reservation,
        ], 200);
    }

    /**
     * cancel a specific reservation .
    */
    public function cancel($id){
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
        
        //change the state of the reservation to canceled
        $reservation->update(['status' => 'canceled']);
        

        return response()->json([
            'message' => 'Reservation canceled successfully',
            'reservation' => $reservation,
        ], 200);
    }


    public function destroy(Reservation $reservation)
    {
        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        // Delete the reservation
        $reservation->delete();

        return response()->json(null, 204);
    }
    
}
