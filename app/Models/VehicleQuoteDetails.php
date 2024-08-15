<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleQuoteDetails extends Model
{
    use HasFactory;
    protected $table = 'vehicle_quote_details';
    protected $guarded=[];

    public function destination(){
        return $this->hasOne(Destination::class, 'id', 'destination_id');
    }
}
