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

            $table->timestamp('order_date')->useCurrent();
            $table->timestamp('deadline');

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');
            $table->foreignId('sales_id')
                ->constrained('sales')
                ->onDelete('cascade');

            $table->foreignId('product_category_id')
                ->constrained('product_categories')
                ->onDelete('cascade');
            $table->string('product_color');

            $table->foreignId('material_category_id')
                ->constrained('material_categories')
                ->onDelete('cascade');
            $table->foreignId('material_texture_id')
                ->constrained('material_textures')
                ->onDelete('cascade');

            $table->text('notes')->nullable();

            $table->foreignId('shipping_id')
                ->constrained('shippings')
                ->onDelete('cascade');

            $table->bigInteger('total_qty')->default(0);
            $table->bigInteger('sub_total')->default(0);
            $table->bigInteger('discount')->default(0);
            $table->bigInteger('final_price')->default(0);

            $table->enum('production_status', ['not_started', 'in_progress', 'completed', 'cancelled'])
                ->default('not_started');

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
