<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use App\Models\Destination;
use Illuminate\Foundation\Http\FormRequest;

class ValidateDestination extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('destination')??Crypt::encrypt(0);
        $id=Crypt::decrypt($id);
        return [
            'destination' => 'required|unique:destinations,destination,'. $id,
            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
}
