<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class CourseRequest extends RequestManager
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
            'course_code' => "required|unique:courses,course_code,{$id},id",
            'course_name' => "required|unique:courses,course_name,{$id},id"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'course_code.unique' => "The course code has already taken.",
            'course_name.unique' => "The course name has already taken."
        ];
    }
}
