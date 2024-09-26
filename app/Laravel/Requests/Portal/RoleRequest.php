<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class RoleRequest extends RequestManager
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
            'role' => "required|unique:roles,name,{$id},id",
            'status' => 'required',
            'permissions' => 'required|array',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'permissions.array' => "Please assign at least 1 permission.",
            'permissions.required' => "Please assign at least 1 permission.",
            'role' => "The Role has already been taken."        
        ];
    }
}
