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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel',350);
            $table->foreignId('destination_id');
            $table->foreignId('category_id')->nullable();
            $table->text('location')->nullable();
            $table->integer('inventory')->default(0);
            $table->string('contact',15)->nullable();
            $table->string('reservation_contact',15);
            $table->string('email');
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->text('image')->nullable();
            $table->integer('status')->default(1);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('hotels');
    }
};
