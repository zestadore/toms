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
        Schema::create('over_dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('old_booking_id');
            $table->foreignId('old_quotation_id');
            $table->foreignId('bank_id')->nullable();
            $table->double('amount',10,2);
            $table->text('payment_details');
            $table->integer('status')->default(0)->comment('0->pending,1->used');
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
        Schema::dropIfExists('over_dues');
    }
};
