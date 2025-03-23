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
        Schema::create('pershilabe_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('Unit_price', 8, 2);
            $table->decimal('current_quantity', 8, 2);
            $table->decimal('minimum_quantity', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pershilabe_products');
    }
};
