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

class ImageRemover {

    public static function remove($file){
        if(File::exists($file)){
            File::deleteDirectory($file);
        } 
    }
}