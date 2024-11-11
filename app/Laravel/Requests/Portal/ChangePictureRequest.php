<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class ChangePictureRequest extends RequestManager
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'profile_picture' => "required|file|image|between:1,10240"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'profile_picture.required' => "Picture attachment is required.",
        ];
    }
}
