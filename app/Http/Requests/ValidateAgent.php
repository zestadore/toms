<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;

class ValidateAgent extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('agent')??Crypt::encrypt(0);
        $id=Crypt::decrypt($id);
        return [
            'company_name' => 'required|unique:agents,company_name,'. $id,
            'email' => 'required|unique:agents,email,'. $id,
            'state'=>'required',
            'contact'=>'required|numeric',
        ];
    }
}
