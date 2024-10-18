<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class ResearchRequest extends RequestManager
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
            'research_file' => "required|file|mimes:doc,docx|between:1,10240",
            'research_type' => "required",
            'title' => "required",
            'chapter' => "required|integer|min:1",
            'version' => "required|numeric|min:1",
            'submit_to' => "required",
            'share_file' => "nullable|array"
        ];

        if($id > 0){
            $rules['research_file'] = 'nullable|file|mimes:doc,docx|between:1,10240';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
        ];
    }
}
