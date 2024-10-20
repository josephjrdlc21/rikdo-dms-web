<?php 

namespace App\Laravel\Services;

class FileDownloader {
    public static function download($path){
        if(file_exists($path)){
            return response()->download($path);
        }
    }
}