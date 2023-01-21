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
        Schema::create('quote_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id');
            $table->string('rev_id',15);
            $table->date('arrival_date');
            $table->integer('no_nights');
            $table->integer('adults');
            $table->integer('kids')->default(0);
            $table->string('meal_plan',10);
            $table->integer('sgl_rooms')->default(0);
            $table->integer('dbl_rooms')->default(0);
            $table->integer('ex_bed_adults')->default(0);
            $table->integer('ex_bed_children')->default(0);
            $table->integer('ex_children_wout')->default(0);
            $table->double('tot_sgl')->default(0);
            $table->double('tot_dbl')->default(0);
            $table->double('tot_ex_bed_adt')->default(0);
            $table->double('tot_bed_chd')->default(0);
            $table->double('tot_chd_wout')->default(0);
            $table->integer('allowed_kms')->default(0);
            $table->double('vehicle_rate')->default(0);
            $table->double('hotel_addons')->default(0);
            $table->double('vehicle_addons')->default(0);
            $table->double('grand_total')->default(0);
            $table->integer('discount_type')->default(0)->comment('0->percent,1->amount');
            $table->double('discount')->default(0);
            $table->double('discount_amount')->default(0);
            $table->double('markup_type')->default(0);
            $table->double('markup')->default(0);
            $table->double('markup_amount')->default(0);
            $table->double('gst')->default(0);
            $table->double('gst_amount')->default(0);
            $table->double('net_rate')->default(0);
            $table->text('note')->nullable();
            $table->integer('status')->comment('0->pending,1->Quotation,2->Confirmed,3->Cancelled');
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
        Schema::dropIfExists('quote_revisions');
    }
};
