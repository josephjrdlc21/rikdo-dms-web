<?php 

namespace App\Laravel\Services;

class FileDownloader {
    public static function download($path){
        if(file_exists($path)){
            return response()->download($path, null, [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }
    }
}