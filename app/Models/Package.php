<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class Package extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'packages';
    protected $guarded=[];
    protected $hidden=['self'];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getSelfAttribute(){
        return $this->attributes['id'];
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

    public function packageRates(){
        return $this->hasMany(PackageRate::class, 'package_id', 'self');
    }

}
