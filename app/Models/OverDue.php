<?php

namespace App\Models;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverDue extends Model
{
    use HasFactory;
    protected $table = 'over_dues';
    protected $guarded=[];

    protected $casts = [
        'new_booking_id' => 'array',
        'new_quotation_id' => 'array',
    ];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function bank(){
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }

    public function quotation(){
        return $this->hasOne(Quotation::class, 'id', 'old_quotation_id');
    }
}
