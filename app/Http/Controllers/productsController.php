<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        return response()->json(['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric',
            'current_quantity' => 'required|numeric',
            'minimum_quantity' => 'required|numeric',
            'type' => 'required|in:non_perishable,perishable',
            'expiration_date' => [
                'nullable',
                'date',
                Rule::requiredIf($request->input('type') === 'perishable'),
            ],
            'category' => 'required|in:ingredient,boisson',
            'fournisseur' => 'nullable|string|max:255',
        ]);

        $product = Products::create([
            'name' => $validated['name'],
            'unit_price' => $validated['unit_price'],
            'current_quantity' => $validated['current_quantity'],
            'minimum_quantity' => $validated['minimum_quantity'],
            'type' => $validated['type'],
            'expiration_date' => $validated['expiration_date'] ?? null,
            'category' => $validated['category'],
            'fournisseur' => $validated['fournisseur'] ?? null,
        ]);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'unit_price' => 'sometimes|required|numeric',
            'current_quantity' => 'sometimes|required|numeric',
            'minimum_quantity' => 'sometimes|required|numeric',
            'type' => 'sometimes|required|in:non_perishable,perishable',
            'category' => 'sometimes|required|in:ingredient,boisson',
            'fournisseur' => 'sometimes|nullable|string|max:255',
            'expiration_date' => [
                'nullable',
                'date',
                Rule::requiredIf($request->input('type') === 'perishable'),
            ],
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }


    
}
