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
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('theater_id')->constrained('theaters')->onDelete('cascade');
            $table->date('show_date');
            $table->string('start_time'); // e.g. "04:30 PM", "08:00 PM", "11:00 PM"
            $table->decimal('price', 8, 2)->default(120.00); // Ticket price in EGP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
