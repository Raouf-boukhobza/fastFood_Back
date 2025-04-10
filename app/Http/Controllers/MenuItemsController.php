<?php

namespace App\Http\Controllers;

use App\Models\MenuItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MenuItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menuItems = MenuItems::all();
        return response()->json([ "Menu Items" => $menuItems], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'catégory_id' => 'required|exists:catégories,id',
            'is_available' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image upload if provided
        $image = null;
        $imageUrl = null;


        if (isset($validated['image']) && $validated['image']) {
            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image));


            $response = Http::withHeaders([
                'Authorization' => 'Client-ID a1de682a11b3b10',
            ])->post('https://api.imgur.com/3/image', [
                'image' => $imageData,
            ]);


            if ($response->successful()) {
                $imageUrl = $response->json()['data']['link'];
            }
        }


        $menuItem = MenuItems::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'catégory_id' => $validated['catégory_id'],
            'is_available' => $validated['is_available'] ?? true,
            'imageUrl' => $imageUrl,
        ]);

        return response()->json([
            'message' => 'Menu item created successfully',
            'menu_item' =>  $menuItem
        ], 201);
    }
    
    

    
    /**
     * Display the specified resource.
     */
    public function show(MenuItems $menuItems)
    {
        return response()->json([
            'menu_item' => $menuItems
        ], 200);
    }
 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItems $menuItem)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'numeric|min:0',
            'category_id' => 'exists:categories,id',
            'is_available' => 'boolean',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle the image upload if provided
        $image = null;
        $imageUrl = null;


        //
        if (isset($validated['image']) && $validated['image']) {
            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image));
    
            $response = Http::withHeaders([
                'Authorization' => 'Client-ID a1de682a11b3b10',
            ])->post('https://api.imgur.com/3/image', [
                'image' => $imageData,
            ]);
    
            if ($response->successful()) {
                $imageUrl = $response->json()['data']['link'];
            }
        }
        // Update the menu item
        $menuItem->update([
            'name' => $validated['name'] ?? $menuItem->name,
            'description' => $validated['description'] ?? $menuItem->description,
            'price' => $validated['price'] ?? $menuItem->price,
            'catégory_id' => $validated['catégory_id'] ?? $menuItem->catégory_id,
            'is_available' => $validated['is_available'] ?? $menuItem->is_available,
            'imageUrl' => $imageUrl ?? $menuItem->imageUrl,
        ]);
    
        return response()->json([
            'message' => 'Menu item updated successfully',
            'menu_item' => $menuItem
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItems $menuItems)
    {
        //
    }
}
