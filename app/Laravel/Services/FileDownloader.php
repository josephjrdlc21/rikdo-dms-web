<?php 

namespace App\Laravel\Services;
/*
*
* Models used for this class
*/

/*
*
* Classes used for this class
*/
use Carbon, Str, File, Image, URL;

class FileDownloader {

    public static function download($path){
        if(file_exists($path)){
            return response()->download($path);
        }
    }
}