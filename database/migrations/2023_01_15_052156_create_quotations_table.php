<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quote_id',15)->unique();
            $table->foreignId('agent_id');
            $table->string('package_name')->nullable();
            $table->string('guest_name')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('assigned_to');
            $table->integer('status')->comment('0->pending,1->Quotation,2->Confirmed,3->Cancelled');
            $table->foreignId('created_by');
            $table->foreignId('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotations');
    }
};
