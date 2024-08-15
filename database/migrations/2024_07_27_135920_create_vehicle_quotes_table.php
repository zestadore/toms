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
        Schema::create('vehicle_quotes', function (Blueprint $table) {
            $table->id();
            $table->integer('nights');
            $table->foreignId('vehicle_id')->constrained();
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->double('vehicle_rate', 10, 2);
            $table->integer('kms_per_day')->default(0);
            $table->double('kms_blocked', 10, 2)->default(0);
            $table->double('add_kms_rate', 10, 2)->default(0);
            $table->double('gross_rate', 10, 2);
            $table->double('gst_percentage', 8, 2);
            $table->double('gst_amount', 10, 2);
            $table->double('net_rate', 10, 2);
            $table->foreignId('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_quotes');
    }
};
