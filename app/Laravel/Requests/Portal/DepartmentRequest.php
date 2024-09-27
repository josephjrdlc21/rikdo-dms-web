<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class DepartmentRequest extends RequestManager
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
            'dept_code' => "required|unique:departments,dept_code,{$id},id",
            'dept_name' => "required|unique:departments,dept_name,{$id},id"
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'dept_code.unique' => "The department code has already taken.",
            'dept_name.unique' => "The department name has already taken."
        ];
    }
}
