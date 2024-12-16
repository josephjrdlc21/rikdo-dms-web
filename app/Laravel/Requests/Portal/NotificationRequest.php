<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class NotificationRequest extends RequestManager
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
            'has_research' => "boolean|nullable",
            'has_student_research' => "boolean|nullable",
            'has_all_research' => "boolean|nullable",
            'has_completed_research' => "boolean|nullable",
            'has_posted_research' => "boolean|nullable"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
