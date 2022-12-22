<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Hotel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'hotels';
    protected $guarded=[];
    protected $appends=['image_path'];

    public function getImagePathAttribute(){
        if($this->attributes['image']!=null){
            return url('/') .'/uploads/hotels/'.$this->attributes['image'];
        }else{
            return null;
        }
    }

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function destination(){
        return $this->hasOne(Destination::class, 'id', 'destination_id');
    }
}
