<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;

class ValidateBank extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('bank')??Crypt::encrypt(0);
        $id=Crypt::decrypt($id);
        return [
            'bank_name' => 'required|unique:banks,bank_name,'. $id,
            'branch' => 'required',
            'account_name'=>'required',
            'account_number'=>'required',
            'ifsc'=>'required',
        ];
    }
}
