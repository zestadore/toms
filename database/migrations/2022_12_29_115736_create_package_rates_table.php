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
        Schema::create('package_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id');
            $table->foreignId('room_category_id');
            $table->double('single',10,2)->default(0);
            $table->double('double',10,2)->default(0);
            $table->double('extra_adult',10,2)->default(0);
            $table->double('extra_child_bed',10,2)->default(0);
            $table->double('extra_child_wout_bed',10,2)->default(0);
            $table->double('breakfast',10,2)->default(0);
            $table->double('lunch',10,2)->default(0);
            $table->double('dinner',10,2)->default(0);
            $table->double('child_breakfast',10,2)->default(0);
            $table->double('child_lunch',10,2)->default(0);
            $table->double('child_dinner',10,2)->default(0);
            $table->json('days')->nullable();
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
        Schema::dropIfExists('package_rates');
    }
};
