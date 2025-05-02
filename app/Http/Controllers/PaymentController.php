<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with('order.orderDetails')->get();
        return response()->json(['payments' => $payments], 200);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment = Payment::with('order.orderDetails')->find($payment->id);
        return response()->json(['payment' => $payment], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'in:pending,paid',
            'method' => 'in:cash,credit card',
        ]);
        $payment->update(array_filter($validated));
        if($validated['status'] == 'paid'){
            $payment->order->update(['status' => 'Payée']);
            $payment->order->table->update(['status' => 'available']);
        }
        return response()->json(['payment' => $payment], 200);
    }
}
