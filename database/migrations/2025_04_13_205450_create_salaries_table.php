<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')-> references('id')->on('employes')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('last_payment_date')->nullable();
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->enum('payment_method' , ["cash" , "bank_transfer" , "check"])->default("cash");
            $table->decimal('primes', 10, 2)->default(0);
            $table->decimal('deduction', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
