<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class ResearchTypeRequest extends RequestManager
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
            'type' => "required|unique:research_types,type,{$id},id",
            'max_chapter' => "required"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'type.unique' => "The research type has already taken.",
        ];
    }
}
