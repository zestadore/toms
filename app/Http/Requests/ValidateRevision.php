<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateRevision extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'arrival_date'=>'required|date',
            'no_nights'=>'required|numeric|min:1',
            'adults'=>'required|numeric|min:1',
            'meal_plan'=>'required',
            'sgl_rooms'=>'integer|required',
            'dbl_rooms'=>'integer|required',
        ];
    }
}
