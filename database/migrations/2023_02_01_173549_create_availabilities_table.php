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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_revision_id');
            $table->text('primary_note')->nullable();
            $table->text('availability_note')->nullable();
            $table->foreignId('created_by');
            $table->foreignId('updated_by')->nullable();
            $table->integer('status')->comment('0->pending,1->checked');
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
        Schema::dropIfExists('availabilities');
    }
};
