<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class PostedResearchRequest extends RequestManager
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
            'research' => "required|unique:posted_research,title"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'research.unique' => "The research has already posted."
        ];
    }
}
