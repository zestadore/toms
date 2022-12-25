<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateDatePlan extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
        ];
    }
}
