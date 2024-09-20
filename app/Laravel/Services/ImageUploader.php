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

class ImageUploader {

	/**
	*
	*@param Illuminate\Support\Facades\File $file
	*@param string $image_directory
	*
	*@return array
	*/
	public static function upload($file, $image_directory = "uploads", $resized_size = 1024, $thumbnail_size = 320){
		
		$storage = env('IMAGE_STORAGE', "file");

		list($width, $height) = getimagesize($file);

		switch (Str::lower($storage)) {
			case 'file':
				// $file = $request->file("file");
				$ext = $file->getClientOriginalExtension();
				$thumbnail = ['height' => 250, 'width' => 250];
				$path_directory = $image_directory;
				$resized_directory = $image_directory."/resized";
				$thumb_directory = $image_directory."/thumbnails";

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}

				if (!File::exists($resized_directory)){
					File::makeDirectory($resized_directory, $mode = 0777, true, true);
				}

				if (!File::exists($thumb_directory)){
					File::makeDirectory($thumb_directory, $mode = 0777, true, true);
				}

				$filename = Helper::create_filename($ext);

				$file->move($path_directory, $filename); 
				if($width >= 1024){
					//if greater than or equalt to 1024 width
					Image::make("{$path_directory}/{$filename}")->interlace()->widen(1024)->save("{$resized_directory}/{$filename}",95);
					Image::make("{$path_directory}/{$filename}")->interlace()->widen(320)->save("{$thumb_directory}/{$filename}",95);
					
				}else{
					Image::make("{$path_directory}/{$filename}")->interlace()->widen($width)->save("{$resized_directory}/{$filename}",95);

					if($width >= 320){
						//if greater than or equalt to 320 width
						Image::make("{$path_directory}/{$filename}")->interlace()->widen(320)->save("{$thumb_directory}/{$filename}",95);
					}else{
						Image::make("{$path_directory}/{$filename}")->interlace()->widen($width)->save("{$thumb_directory}/{$filename}",95);
					}
					
				}

				return [ 
					"path" => $image_directory, 
					"directory" => URL::to($path_directory), 
					"filename" => $filename ,
					"width" => $width,
					"height" => $height,
					"source" => $storage
				];

			break;
			
			default:
				return array();
			break;
		}
	}
}