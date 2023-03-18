<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Bank extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'banks';
    protected $guarded=[];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }
}
