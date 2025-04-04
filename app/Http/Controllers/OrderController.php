<?php

namespace App\Http\Controllers;

use App\Models\MenuItems;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Packs;
use App\Models\Payment;
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

        $validate = $request->validate([
                'table_id' => 'required|integer|exists:tables,id',
                'type' => 'required|in:A table,emporter,Livraison',
                'esstimation_time' => 'date_format:H:i:s',
                'delivry_adress' => 'required_if:type,Livraison',
                'delivry_phone' => 'required_if:type,Livraison',
                'orderDetails' => 'required|array|min:1',
                'orderDetails.*.item_id' => 'nullable|exists:menu_items,id',
                'orderDetails.*.pack_id' => 'nullable|exists:pack_id,id',
                'orderDetails.*.quantity' => 'required|integer|min:1',
            ]);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'date' => now(),
            'table_id'=> $validate['table_id'],
            'type'=>$validate['type'],
            'esstimation_time'=>$validate['esstimation_time'] ,
            'delivry_adress'=>$validate['delivry_adress']?? null,
            'delivry_phone'=>$validate['delivry_phone']?? null,

        ]);

        foreach($validate['orderDetails'] as $orderDetail){
            $price = $orderDetail['item_id'] 
            ? (MenuItems::find($orderDetail['item_id'])->price) * $orderDetail['quantity']
            : (Packs::find($orderDetail['pack_id'])->price) * $orderDetail['quantity'];
            $order->orderDetails()->create([
                'item_id' => $orderDetail['item_id'] ?? null,
                'pack_id' => $orderDetail['pack_id']?? null,
                'quantity' => $orderDetail['quantity'],
                'price' => $price,
                ]
        );
        }

        //create the payment for the order 
        Payment::create([
            'order_id' => $order->id,
            'amount' => $order->orderDetails->sum('price'),
            'date' => now(),
        ]);
        
        return response()->json(['message' => 'order created successfully' , 'order' => $order->load("orderDetails")],201);
    }



    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json(['order' => $order->load("orderDetails")],200);
    }   

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Order $order)
    {
            $validate = $request->validate([
                'table_id' => 'integer|exists:tables,id',
                'type' => 'in:A table,emporter,Livraison',
                'status' => 'in:En attente,En préparation,Prête , canceled',
                'esstimation_time' => 'date_format:H:i:s',
                'delivry_adress' => 'required_if:type,Livraison',
                'delivry_phone' => 'required_if:type,Livraison',
                'orderDetails' => 'array|min:1',
                'orderDetails.*.id' => 'nullable|exists:order_details,id',
                'orderDetails.*.item_id' => 'nullable|exists:menu_items,id',
                'orderDetails.*.pack_id' => 'nullable|exists:pack_id,id',
                'orderDetails.*.quantity' => 'required|integer|min:1',
            ]);


            //update the order 
            $order->update(collect($validate)->except('orderDetails')->toArray());


            // update the order details
            foreach($validate['orderDetails'] as $orderDetail){
                $price = $orderDetail['item_id'] 
                ? (MenuItems::find($orderDetail['item_id'])->price) * $orderDetail['quantity']
                : (Packs::find($orderDetail['pack_id'])->price) * $orderDetail['quantity'];

                $order->orderDetails()->updateOrCreate(
                [   'id' => $orderDetail['id'] ?? null,],
                [   'item_id' => $orderDetail['item_id'],
                    'pack_id' => $orderDetail['pack_id'] ?? null,
                    'quantity' => $orderDetail['quantity'] ?? null,
                    'price' => $price,
                ]
            );

            }
            //update the payment
            $order->payment->update([
                'amount' => $order->orderDetails->sum('price'),
            ]);
            return response()->json(['message' => 'order updated successfully' , 'order' => $order->load("orderDetails")],200);
        
    }
}
