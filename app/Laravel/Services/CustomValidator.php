<?php

namespace App\Laravel\Services;

use App\Laravel\Models\{User,UserKYC,Research,ResearchType,CompletedResearch};
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

    public function validatePasswordFormat($attribute, $value, $parameters){
        return preg_match(("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-.]).{8,}$/"), $value);
    }

    public function validateIsTitleApproved($attribute, $value, $parameters){
        $research = Research::where('title', $value)->first();
        $chapter = ResearchType::where('id', $research->research_type_id)->first();

        $count = Research::where('title', $research->title)
            ->where('chapter', $chapter->max)
            ->where('status', 'approved')
            ->count();

        return $count === 1;
    }

    public function validateUniqueTitle($attribute, $value, $parameters){
        $id = (is_array($parameters) and isset($parameters[0])) ? $parameters[0] : "0";

        $research = CompletedResearch::where('title', $value)->where('id', '<>', $id)->first();

        if ($research) {
            if ($research->status === 're_submission' AND $research->id == "0") {
                return true;
            }
            
            return false;
        }
    
        return true;
    }
}
