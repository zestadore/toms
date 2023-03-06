<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Quotation;

class QuoteRevision extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'quote_revisions';
    protected $guarded=[];
    protected $appends=['self','revision_count','accomodation_cost'];
    protected $hidden=['self'];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getSelfAttribute(){
        return $this->attributes['id'];
    }

    public function getRevisionCountAttribute(){
        $quote=Quotation::find($this->attributes['quotation_id']);
        if($quote->type>0){
            $count=1;
        }else{
            $count=$this->revisionDetails()->count();
        }
        return $count;
    }

    public function getAccomodationCostAttribute(){
        return $this->attributes['tot_sgl']+$this->attributes['tot_dbl']+$this->attributes['tot_ex_bed_adt']+$this->attributes['tot_bed_chd']+$this->attributes['tot_chd_wout'];
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

    public function quotation(){
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }

    public function revisionDetails(){
        return $this->hasMany(QuoteRevisionDetail::class, 'revision_id', 'self');
    }

    public function vehicle(){
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }


}
