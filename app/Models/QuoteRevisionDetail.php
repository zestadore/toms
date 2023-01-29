<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class QuoteRevisionDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'quote_revision_details';
    protected $guarded=[];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = Auth::user()->id;
            $model->updated_by = Auth::user()->id;
        });
        static::updating(function($model)
        {
            $model->updated_by = Auth::user()->id;
        });
    }
    
    public function destination(){
        return $this->hasOne(Destination::class, 'id', 'destination_id');
    }

    public function hotel(){
        return $this->hasOne(Hotel::class, 'id', 'hotel_id');
    }

    public function roomCategory(){
        return $this->hasOne(RoomCategory::class, 'id', 'room_category_id');
    }

}
