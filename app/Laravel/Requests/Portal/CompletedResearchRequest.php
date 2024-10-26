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
        $id = $this->id ?? 0;

        $rules = [
            'title' => "required|unique_title:{$id}|is_title_approved",
            'research_file' => "required|file|mimes:pdf|between:1,10240",
            'authors' => "required|array",
            'abstract' => "required"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'title.is_title_approved' => "This title has not yet been approved and reached the final chapter",
            'title.unique_title' => "This title has been already submitted.",
            'authors.array' => "Please assign at least 1 author.",
            'authors.required' => "Please assign at least 1 author.",
        ];
    }
}
