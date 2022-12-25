<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id');
            $table->date('valid_from')->comment('including_from');
            $table->date('valid_to')->comment('including_to');
            $table->text('description')->nullable();
            $table->integer('status')->default(1);
            $table->foreignId('created_by');
            $table->foreignId('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_plans');
    }
};
