<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePackageRate extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_category' => 'required',
            'single' => 'required|numeric',
            'double' => 'required|numeric',
            'extra_adult' => 'required|numeric',
            'extra_child_bed' => 'required|numeric',
            'extra_child_wout_bed' => 'required|numeric',
            'breakfast' => 'required|numeric',
            'child_breakfast' => 'required|numeric',
            'lunch' => 'required|numeric',
            'child_lunch' => 'required|numeric',
            'dinner' => 'required|numeric',
            'child_dinner' => 'required|numeric',
        ];
    }
}
