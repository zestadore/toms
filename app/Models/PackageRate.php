<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\RoomCategory;

class PackageRate extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'package_rates';
    protected $guarded=[];
    protected $appends=['room_name'];

    protected $casts = [
        'days' => 'array'
    ];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getRoomNameAttribute(){
        $roomCategory=$this->attributes['room_category_id'];
        $room=RoomCategory::find($roomCategory);
        return $room->room_category;

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

    public function room(){
        return $this->hasOne(RoomCategory::class, 'id', 'room_category_id');
    }

    public function package(){
        return $this->hasOne(Package::class, 'id', 'package_id');
    }
}
