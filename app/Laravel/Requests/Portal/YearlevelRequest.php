<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class YearlevelRequest extends RequestManager
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
        $id = $this->id ?? 0;

        $rules = [
            'yearlevel_name' => "required|unique:yearlevels,yearlevel_name,{$id},id"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'yearlevel_name.unique' => "The yearlevel name has already taken."
        ];
    }
}
