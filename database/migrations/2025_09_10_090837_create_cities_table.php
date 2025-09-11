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
        Schema::create('cities', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('province_id') // province_id INT
                ->constrained('provinces') // FOREIGN KEY province_id REFERENCES provinces(id)
                ->onDelete('cascade');     // kalau province dihapus, semua city ikut kehapus
            $table->string('name', 100); // name VARCHAR(100) UNIQUE NOT NULL
            $table->timestamps(); // created_at & updated_at (default CURRENT_TIMESTAMP)
            $table->unique(['province_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
