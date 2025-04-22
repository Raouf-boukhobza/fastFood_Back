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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('Unit_price', 8, 2);
            $table->decimal('current_quantity', 8, 2);
            $table->decimal('minimum_quantity', 8, 2);
            $table->enum("type", ['non_perishable', 'perishable']);
            $table->enum('category' , ['ingredient' , 'boisson']);
            $table->String('fournisseur')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_pershilabe_products');
    }
};
