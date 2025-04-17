<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;
use Whoops\Exception\Formatter;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salaries = Salary::all()->map(function ($salary) {
            $salary->total = $salary->amount + $salary->primes - $salary->deduction;
            return $salary;
        });

        return response()->json($salaries);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id' => 'required|exists:employes,id',
            'amount' => 'required|numeric',
            'last_payment_date' => 'nullable|date',
            'primes' => 'nullable|numeric',
            'status' => 'in:paid,pending',
            'deduction' => 'nullable|numeric',
            'payment_method' => 'in:cash,bank_transfer,check',

        ]);

        $salary = Salary::create([
            'employe_id' => $validated['employe_id'],
            'amount' => $validated['amount'],
            'last_payment_date' => $validated['last_payment_date'] ?? null,
            'status' => $validated['status'] ?? 'pending',
            'primes' => $validated['primes'] ?? 0,
            'deduction' => $validated['deduction'] ?? 0,
            'payment_method' => $validated['payment_method'],
        ]);

        return response()->json($salary, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        $salary->total = $salary->amount + $salary->primes - $salary->deduction;
        return response()->json($salary);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'employe_id' => 'sometimes|required|exists:employes,id',
            'amount' => 'sometimes|required|numeric',
            'last_payment_date' => 'sometimes|date',
            'primes' => 'sometimes|nullable|numeric',
            'status' => 'sometimes|in:paid,pending',
            'deduction' => 'sometimes|nullable|numeric',
            'payment_method' => 'sometimes|in:cash,bank_transfer,check',
        ]);

        $salary->update([
            'employe_id' => $validated['employe_id'] ?? $salary->employe_id,
            'amount' => $validated['amount'] ?? $salary->amount,
            'last_payment_date' => $validated['last_payment_date'] ?? $salary->last_payment_date,
            'status' => $validated['status'] ?? $salary->status,
            'primes' => $validated['primes'] ?? $salary->primes,
            'deduction' => $validated['deduction'] ?? $salary->deduction,
            'payment_method' => $validated['payment_method'] ?? $salary->payment_method,
        ]);

        return response()->json($salary);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return response()->json(['message' => 'Salary deleted successfully']);
    }
}
