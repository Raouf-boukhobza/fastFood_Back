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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_phone')->nullable();
            $table->foreignId('tables_id')->references('id')->on('tables')->onDelete('cascade');
            $table->date('date');
            $table->time('hour');
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');
            $table->decimal('duration' , 4 , 2) ->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
