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
        Schema::create('theaters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Hall 1 - IMAX Grand", "Hall 2 - VIP Lounge"
            $table->string('type')->default('Standard'); // e.g. "IMAX 3D", "Dolby Atmos", "VIP", "Standard"
            $table->integer('rows_count')->default(6); // Rows: A, B, C, D, E, F
            $table->integer('seats_per_row')->default(10); // 10 seats per row -> 60 seats total
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theaters');
    }
};
