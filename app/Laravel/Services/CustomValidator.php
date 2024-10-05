<?php

namespace App\Laravel\Services;

use App\Laravel\Models\{User,UserKYC};
use Illuminate\Validation\Validator;

use Hash,PhoneNumber;

class CustomValidator extends Validator{
    
    public function validateNameFormat($attribute, $value, $parameters){
        return preg_match('/^[a-zA-Z0-9.\-\s]+$/', $value);
    }

    public function validateIdNumberFormat($attribute, $value, $parameters) {
        return preg_match('/^\d{8}$/', $value);
    }

    public function validateUniqueIdNumber($attribute, $value, $parameters){
        $id_number = strtolower($value);
        $id = (is_array($parameters) and isset($parameters[0])) ? $parameters[0] : "0";
        $type = (is_array($parameters) and isset($parameters[1])) ? $parameters[1] : "user";

        switch (strtolower($type)) {
            case 'portal':
                return User::where('id', '<>', $id)
                    ->whereHas('user_info', function ($query) use ($id_number) {
                        $query->where('id_number', $id_number);
                    })
                    ->count() > 0 ? false : true;
                break;
            case 'register':
                $user = User::where('id', '<>', $id)
                    ->whereHas('user_info', function ($query) use ($id_number) {
                        $query->where('id_number', $id_number);
                    })
                    ->count() > 0;

                $user_kyc = UserKYC::where('id_number', $id_number)
                    ->where('id', '<>', $id)
                    ->count() > 0;

                if($user || $user_kyc){
                    return false;
                }
                
                return true;
                break;
            default:
                return User::where('id_number', $id_number)
                    ->where('id', '<>', $id)
                    ->count() ? false : true;
        }
    }

    public function validateUniqueEmail($attribute, $value, $parameters){
        $email = strtolower($value);
        $id = (is_array($parameters) and isset($parameters[0])) ? $parameters[0] : "0";
        $type = (is_array($parameters) and isset($parameters[1])) ? $parameters[1] : "user";

        switch (strtolower($type)) {
            case 'portal':
                return User::where('id', '<>', $id)
                    ->whereHas('user_info', function ($query) use ($email) {
                        $query->where('email', $email);
                    })
                    ->count() > 0 ? false : true;
                break;
            case 'register':
                $user = User::where('id', '<>', $id)
                ->whereHas('user_info', function ($query) use ($email) {
                    $query->where('email', $email);
                })
                ->count() > 0;

                $user_kyc = UserKYC::where('email', $email)
                    ->where('id', '<>', $id)
                    ->count() > 0;

                if($user || $user_kyc){
                    return false;
                }
                
                return true;
                break;
            default:
                return User::where('email', $email)
                    ->where('id', '<>', $id)
                    ->count() ? false : true;
        }
    }

    public function validateUniquePhone($attribute, $value, $parameters){
        $id = (is_array($parameters) and isset($parameters[0])) ? $parameters[0] : "0";
        $type = (is_array($parameters) and isset($parameters[1])) ? $parameters[1] : "user";

        try{
            $contact_number = new PhoneNumber($value);

            if (is_null($contact_number->getCountry())) {
                $contact_number = new PhoneNumber($value, "PH");
            }

            $contact_number = $contact_number->formatE164();
        }catch(\Exception $e){
           return false; 
        }

        switch (strtolower($type)) {
            case 'portal':
                return  User::where('id', '<>', $id)
                    ->whereHas('user_info', function ($query) use ($contact_number) {
                        $query->where('contact_number', $contact_number);
                    })
                    ->count() > 0 ? false : true;
                break;
            case 'register':
                $user = User::where('id', '<>', $id)
                ->whereHas('user_info', function ($query) use ($contact_number) {
                    $query->where('contact_number', $contact_number);
                })
                ->count() > 0;

                $user_kyc = UserKYC::where('contact_number', $contact_number)
                    ->where('id', '<>', $id)
                    ->count() > 0;

                if($user || $user_kyc){
                    return false;
                }
                
                return true;                
                break;
            default:
                return User::where('contact_number', $contact_number)
                    ->where('id', '<>', $id)
                    ->count() ? false : true;
        }
    }
}
