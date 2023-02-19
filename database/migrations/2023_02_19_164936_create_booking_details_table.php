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
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id');
            $table->foreignId('quote_revision_details_id');
            $table->integer('status')->default(0);
            $table->double('single',10,2)->default(0);
            $table->double('double',10,2)->default(0);
            $table->double('extra_adult',10,2)->default(0);
            $table->double('extra_child_bed',10,2)->default(0);
            $table->double('extra_child_wout_bed',10,2)->default(0);
            $table->foreignId('created_by');
            $table->foreignId('updated_by');
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
        Schema::dropIfExists('booking_details');
    }
};
