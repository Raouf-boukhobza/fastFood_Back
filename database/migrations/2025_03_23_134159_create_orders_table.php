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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['En attente', 'En préparation', 'Prête','Payée']);
            $table->decimal('total_price', 8, 2);
            $table->enum('type', ['A table', 'emporter' , 'Livraison']);
            $table->time("esstimation_time");
            $table->string('delery_adress');
            $table->string('delery_phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
