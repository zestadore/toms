<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Vehicle extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'vehicles';
    protected $guarded=[];
    protected $appends=['vehicle_name'];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getVehicleNameAttribute(){
        return $this->attributes['condition'] . ' ' . $this->attributes['vehicle'];
    }
}
