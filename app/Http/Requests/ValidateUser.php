<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;

class ValidateUser extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id=$this->route('member')??Crypt::encrypt(0);
        $id = Crypt::decrypt($id);
        return [
            'email' => 'required|unique:users,email,'. $id,
            'mobile' => 'required|unique:users,mobile,'. $id,
            'password'=>'required|string|min:8|confirmed',
            'first_name'=>'required'
        ];
    }
}
