<?php

namespace App\Http\Controllers;

use App\Models\PackDetails;
use App\Models\Packs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packs = Packs::with('packDetails')->get();
        return response()->json(['packs' => $packs], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|integer|min:0',
            'is_available' => 'boolean',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:5120',
            'pack_details' => 'required|array|min:1',
            'pack_details.*.item_id' => 'required|integer|exists:menu_items,id',
            'pack_details.*.quantity' => 'required|integer|min:1',
        ]);

        // Handle the image upload if provided
        // Handle the image upload if provided
        $image = null;
        $imageUrl = null;


        if ($request->hasFile('image')) {
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

            $pack = Packs::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => (int)$validated['price'],
                'is_available' => $validated['is_available'] ?? true,
                'imageUrl' => $imageUrl,
            ]);

            foreach ($validated['pack_details'] as $packDetail) {
                $pack->packDetails()->create([
                    'item_id' => $packDetail['item_id'],
                    'quantity' => $packDetail['quantity'],
                ]);
            }

            return response()->json([
                'message' => 'Pack created successfully',
                'pack' => $pack->load('packDetails'),
            ], 201);
        }
    

    /**
     * Display the specified resource.
     */
    public function show(Packs $packs)
    {
        $pack = Packs::with('packDetails')->find($packs->id);
        return response()->json(['pack' => $pack], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Packs $pack)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string|max:1000',
            'price' => 'numeric|integer|min:0',
            'is_available' => 'boolean',
            'image' => 'mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:5120',
        ]);

        // Handle the image upload if provided
        $image = null;
        $imageUrl = null;

        if ($request->hasFile('image')) {
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

        $pack->update([
            'name' => $validated['name'] ?? $pack->name,
            'description' => $validated['description'] ?? $pack->description,
            'price' => (int)($validated['price'] ?? $pack->price),
            'is_available' => $validated['is_available'] ?? $pack->is_available,
            'imageUrl' => $imageUrl ?? $pack->imageUrl,
        ]);

        return response()->json(['pack' => $pack], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Packs $packs)
    {
        //
    }
}
