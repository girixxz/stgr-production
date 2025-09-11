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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');
            $table->foreignId('design_variant_id')
                ->constrained('design_variants')
                ->onDelete('cascade');

            $table->foreignId('sleeve_id')
                ->constrained('material_sleeves')
                ->onDelete('cascade');

            $table->foreignId('size_id')
                ->constrained('material_sizes')
                ->onDelete('cascade');

            $table->integer('quantity');
            $table->bigInteger('unit_price');   // pakai bigInteger untuk Rupiah
            $table->bigInteger('subtotal');     // quantity * unit_price

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
