<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ValidateHotel extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hotel' => 'required',
            'destination_id'=>'required',
            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'reservation_contact'=>'required|numeric',
            'email'=>'required|email'
        ];
    }
}
