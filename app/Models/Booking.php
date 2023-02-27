<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\QuoteRevision;
use Illuminate\Support\Facades\Crypt;

class Booking extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'bookings';
    protected $guarded=[];
    protected $appends=['quote_revision'];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getQuoteRevisionAttribute(){
        $revision=QuoteRevision::join('quotations','quote_revisions.quotation_id','quotations.id')->select(['quote_revisions.*','quotations.quote_id'])->find($this->attributes['quote_revision_id']);
        return $revision->quote_id. '/'. $revision->rev_id;
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
}
