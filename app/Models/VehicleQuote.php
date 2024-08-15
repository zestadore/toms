<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleQuote extends Model
{
    use HasFactory;
    protected $table = 'vehicle_quotes';
    protected $guarded=[];

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = Auth::user()->id;
        });
    }

    public function details(){
        return $this->hasMany(VehicleQuoteDetails::class, 'vehicle_quote_id', 'id');
    }

    public function vehicle(){
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }
}
