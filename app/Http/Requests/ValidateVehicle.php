<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;

class ValidateVehicle extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('vehicle')??Crypt::encrypt(0);
        $id=Crypt::decrypt($id);
        return [
            'vehicle' => 'required|unique:vehicles,vehicle,'. $id,
            'condition' => 'required',
            'kms_allowed'=>'required|numeric',
            'rate'=>'required|numeric',
        ];
    }
}
