<?php

namespace App\Http\Controllers;

use App\Models\Tables;
use Illuminate\Http\Request;

class TablesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Tables::all();
        return response()->json(["tables" => $tables]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated =  $request->validate([
            'num_table' => 'required|integer|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $table = Tables::create($validated);

        return response()->json(["table" => $table], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tables $table)
    {
        return response()->json(["table" => $table]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tables $table)
    {
        $request->validate([
            'num_table' => 'integer|max:255',
            'capacity' => 'integer|min:1',
        ]);

        $table->update([
            'num_table' => $request->num_table,
            'capacity' => $request->capacity ?? $table->capacity,
        ]);

        return response()->json(["table" => $table]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tables $table)
    {
        $table->delete();
        return response()->json(["message" => "Table deleted successfully"]);
    }
}
