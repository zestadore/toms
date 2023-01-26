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
        Schema::create('quote_revision_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revision_id');
            $table->foreignId('destination_id');
            $table->foreignId('hotel_id');
            $table->foreignId('room_category_id');
            $table->date('checkin');
            $table->double('single',10,2)->default(0);
            $table->double('double',10,2)->default(0);
            $table->double('extra_adult',10,2)->default(0);
            $table->double('extra_child_bed',10,2)->default(0);
            $table->double('extra_child_wout_bed',10,2)->default(0);
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
        Schema::dropIfExists('quote_revision_details');
    }
};
