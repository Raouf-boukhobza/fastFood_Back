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
        Schema::create('pack_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pack_id')->references('id')->on('packs')->onDelete('cascade');
            $table->foreignId('item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pack_details');
    }
};
