<?php

namespace App\Laravel\Requests\Portal;

use App\Laravel\Requests\RequestManager;

class RegisterRequest extends RequestManager
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

        $current_progress = $this->session()->get('current_progress', '1');

        switch ($current_progress) {
            case '1':
                return [
                    'firstname' => "required|name_format|min:2",
                    'middlename' => "nullable|name_format|min:2",
                    'lastname' => "required|name_format|min:2",
                    'suffix' => "nullable|name_format",
                    'birthdate' => "required|date|date_format:Y-m-d|before:today",
                    'contact' => "required|phone:PH|unique_phone:{$id},register",
                    'email' => "required|email:rfc,dns|unique_email:{$id},register",
                    'address' => "required"
                ];
            case '2':
                return [
                    'id_number' => "required|id_number_format|unique_id_number:{$id},register",
                    'role' => "required",
                    'department' => "nullable",
                    'course' => "nullable",
                    'yearlevel' => "nullable"
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'required' => "Field is required.",
            'name_format' => "Invalid input. Only special characters like period ( . ) and hyphen ( - ) are allowed.",
            'firstname.min' => "The first name must be at least 2 characters.",
            'middlename.min' => "The middle name must be at least 2 characters.",
            'lastname.min' => "The last name must be at least 2 characters.",
            'birthdate.before' => "The birthdate must be a date before today.",
            'birthdate.date_format' => "The birthdate must be in the format YYYY-MM-DD.",
            'contact.unique_phone' => "Phone number already exists.",
            'contact.phone' => "Invalid PH phone number.",
            'email.email' => "Invalid email address.",
            'email.unique_email' => "Email address is already used.",
            'id_number.unique_id_number' => "ID number already exists.",
            'id_number.id_number_format' => "Invalid ID number."
        ];
    }
}
