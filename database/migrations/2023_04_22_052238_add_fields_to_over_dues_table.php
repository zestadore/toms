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
        Schema::table('over_dues', function (Blueprint $table) {
            $table->json('new_booking_id')->nullable()->after('bank_id');
            $table->json('new_quotation_id')->nullable()->after('new_booking_id');
            $table->double('balance_amount')->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('over_dues', function (Blueprint $table) {
            //
        });
    }
};
