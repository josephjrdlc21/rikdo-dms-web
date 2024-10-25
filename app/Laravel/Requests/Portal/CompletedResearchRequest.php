<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class CompletedResearchRequest extends RequestManager
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
            'title' => "required|unique_title|is_title_approved",
            'research_file' => "required|file|mimes:pdf|between:1,10240",
            'abstract' => "required"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'title.is_title_approved' => "This title has not yet been approved and reached the final chapter",
            'title.unique_title' => "This title has been already submitted."
        ];
    }
}
