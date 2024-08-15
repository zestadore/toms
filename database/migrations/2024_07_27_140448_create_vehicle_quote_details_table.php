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
        Schema::create('vehicle_quote_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_quote_id')->constrained();
            $table->foreignId('destination_id')->constrained();
            $table->date('date');
            $table->foreignId('itinerary_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_quote_details');
    }
};
