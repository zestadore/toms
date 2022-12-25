<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DatePlan extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'date_plans';
    protected $guarded=[];
    protected $appends=['valid_from_format','valid_to_format'];

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

    public function getValidFromFormatAttribute(){
        return Carbon::parse($this->attributes['valid_from'])->format('d-M-Y');
    }

    public function getValidToFormatAttribute(){
        return Carbon::parse($this->attributes['valid_to'])->format('d-M-Y');
    }

    public function hotel(){
        return $this->hasOne(Hotel::class, 'id', 'hotel_id');
    }
}
