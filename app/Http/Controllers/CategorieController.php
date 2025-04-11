<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json(["catégories" => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categorie = Categorie::create([
            'name' => $request->name,
        ]);

        return response()->json($categorie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        return response()->json($categorie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categorie->update([
            'name' => $request->name,
        ]);

        return response()->json($categorie, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return response()->json(null, 204);
    }
}
