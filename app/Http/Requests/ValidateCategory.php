<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;

class ValidateCategory extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('category')??Crypt::encrypt(0);
        $id=Crypt::decrypt($id);
        return [
            'category' => 'required|unique:categories,category,'. $id,
        ];
    }
}
