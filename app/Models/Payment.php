<?php

namespace App\Models;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $guarded=[];

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = Auth::user()->id;
        });
        static::updating(function($model)
        {
            $model->updated_by = Auth::user()->id;
        });
    }

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function bank(){
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }

    public function quotation(){
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }

}
