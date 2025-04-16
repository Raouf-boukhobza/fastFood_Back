<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employes = Employe::all();
        return response()->json(["employes" => $employes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:255|unique:employes',
            'role' => 'required|in:Gérant,Serveur,Cuisinier,Caissier',
        ]);

        $employe = Employe::create($validated);
        return response()->json($employe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employe $employe)
    {
        return response()->json($employe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employe $employe)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'lastName' => 'sometimes|required|string|max:255',
            'phoneNumber' => 'sometimes|required|string|max:255|unique:employes,phoneNumber,' . $employe->id,
            'role' => 'sometimes|required|in:Gérant,Serveur,Cuisinier,Caissier',
        ]);

        $employe->update($validated);
        return response()->json($employe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employe $employe)
    {
        $employe->delete();
        return response()->json(null, 204);
    }
}
