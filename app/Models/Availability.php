<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use App\Models\QuoteRevision;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Availability extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'availabilities';
    protected $guarded=[];
    protected $appends=['quote_revision','created_by_name'];

    public function getIdAttribute(){
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getCreatedByNameAttribute(){
        $user=User::find($this->attributes['created_by']);
        return $user->first_name . ' ' . $user->last_name;
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
