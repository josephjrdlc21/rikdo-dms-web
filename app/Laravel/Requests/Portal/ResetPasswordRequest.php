<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class ResetPasswordRequest extends RequestManager
{
    public function rules()
    {
        $rules = [
            'password' => "required|password_format|confirmed",
            'password_confirmation' => "required"
        ];
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'required'	=> "Field is required.",
            'confirmed' => "Password mismatch.",
            'password_format' => "Password must be atleast 12 characters long, should contain atleast 1 uppercase, 1 lowercase, 1 numeric and 1 special character."
        ];
    }
}
