<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Itinerary extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'itineraries';
    protected $guarded=[];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function destination(){
        return $this->hasOne(Destination::class, 'id', 'destination_id');
    }
}
