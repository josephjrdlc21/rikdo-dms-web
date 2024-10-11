<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class ForgotPasswordRequest extends RequestManager
{
    public function rules()
    {
        $rules = [
            'email' => "required|email:rfc,dns"
        ];
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'required'	=> "Field is required."
        ];
    }
}
