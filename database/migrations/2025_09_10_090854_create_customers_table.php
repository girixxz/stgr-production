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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // name VARCHAR(100) UNIQUE NOT NULL
            $table->string('phone', 20)->unique(); // name VARCHAR(100) UNIQUE NOT NULL
            $table->foreignId('province_id') // province_id INT
                ->constrained('provinces') // FOREIGN KEY province_id REFERENCES provinces(id)
                ->onDelete('cascade');
            $table->foreignId('city_id') // province_id INT
                ->constrained('cities') // FOREIGN KEY province_id REFERENCES provinces(id)
                ->onDelete('cascade');
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
