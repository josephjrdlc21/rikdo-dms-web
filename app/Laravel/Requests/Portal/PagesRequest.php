<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class PagesRequest extends RequestManager
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
            'type' => "required|unique:pages,type,{$id},id",
            'title' => "required",
            'content' => "required"
        ];

        if($id > 0){
            $rules['type'] = "nullable|unique:pages,type,{$id},id"; 
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'type.unique' => "The page type has already taken.",
        ];
    }
}
