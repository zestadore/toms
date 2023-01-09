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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->text('company_name');
            $table->text('address')->nullable();
            $table->string('state');
            $table->string('contact',15);
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('person_contact')->nullable();
            $table->string('person_email')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('agents');
    }
};
