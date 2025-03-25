<?php

namespace App\Http\Controllers;

use App\Models\MenuItems;
use App\Models\Order;
use App\Models\Packs;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('orderDetails')->get();
        return response()->json(['orders' => $orders],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validate = $request->validate(
            [
                
                'status' => 'required|in:En attente,En préparation,Prête,Annulée',
                'table_id' => 'required|integer|exists:tables,id',
                'type' => 'required|in:A table,emporter,Livraison',
                'esstimation_time' => 'required|date_format:H:i:s',
                'delery_adress' => 'required_if:type,Livraison',
                'delery_phone' => 'required_if:type,Livraison',
                'orderDetails' => 'required|array|min:1',
                'orderDetails.*.item_id' => 'nullable|exists:menu_items,id',
                'orderDetails.*.pack_id' => 'nullable|exists:pack_id,id',
                'orderDetails.*.quantity' => 'required|integer|min:1',
            ]
            );

        $order = Order::create([
            'user_id' => $request->user()->id,
            'date' => now(),
            'table_id'=> $validate['table_id'],
            'status'=>$validate['status'],
            'type'=>$validate['type'],
            'total_price' => 0,

            'esstimation_time'=>$validate['esstimation_time'],
            'delery_adress'=> $validate['delery_adress'],
             'delery_phone'=>$validate['delery_phone'],
        ]);

        foreach($validate['orderDetails'] as $orderDetail){
            $price = $orderDetail['item_id'] 
            ? (MenuItems::find($orderDetail['item_id'])->price) 
            : (Packs::find($orderDetail['pack_id'])->price) * $orderDetail['quantity'];

            $order->orderDetails()->create([
                'item_id' => $orderDetail['item_id'],
                'pack_id' => $orderDetail['pack_id'],
                'quantity' => $orderDetail['quantity'],
                'price' => $price,
            ]);
        }
        return response()->json(['order' => $order->load("orderDetails")],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
