<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePackage extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'package' => 'required',
            'no_nights' => 'required|numeric|min:1',
            'meal_plan' => 'required',
        ];
    }
}
