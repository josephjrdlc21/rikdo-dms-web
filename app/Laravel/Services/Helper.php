<?php

namespace App\Laravel\Services;

use Curl;
use Log;
use Str;
use PhoneNumber;

class Helper{
    
    public static function create_filename($extension){
        return Str::lower(hash('xxh64', Str::random(10)) . "." . $extension);
    }

    public static function nice_display($string){
        return Str::title(str_replace("_", " ", $string));
    }

    /**
     * Parse date to the standard sql date format
     *
     * @param date $time
     * @param string $format
     *
     * @return Date
     */
    public static function date_db($time){
        return $time == "0000-00-00 00:00:00" ? "" : date(env('DATE_DB', "Y-m-d"), strtotime($time));
    }

    /**
     * Parse date to the specified format
     *
     * @param date $time
     * @param string $format
     *
     * @return Date
     */
    public static function date_format($time, $format = "M d, Y @ h:i a"){
        return $time == "0000-00-00 00:00:00" ? "" : date($format, strtotime($time));
    }

    public static function badge_status($group){
        $result = "default";

        switch (Str::lower($group)) {
            case 'active':$result = "success";
                break;
            case 'inactive':$result = "danger";
                break;
        }
        return $result;
    }

    public static function date_only($time){
        return Self::date_format($time, "F d, Y");
    }

    public static function get_alert_color($status){
        $result = "default";
        switch (Str::lower($status)) {
            case 'failed':
            case 'error':
            case 'danger':
                $result = "danger";
                break;
            case 'success':
                $result = "success";
                break;
            case 'info':
                $result = "info";
                break;
            case 'warning':
                $result = "warning";
                break;
        }
        return $result;
    }

    public static function format_phone($contact_number){
        $contact_number = new PhoneNumber($contact_number);

        if (is_null($contact_number->getCountry())) {
            $contact_number = new PhoneNumber($contact_number, "PH");
        }

        $contact_number = $contact_number->formatE164();
        return $contact_number;
    }

    public static function capitalize_text($text){
        return ucwords($text);
    }
}
