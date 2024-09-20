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

class FileUploader {

	/**
	*
	*@param Illuminate\Support\Facades\File $file
	*@param string $file_directory
	*
	*@return array
	*/
	public static function upload($file, $file_directory = "uploads",$storage = "file"){
		
		$storage = env('FILE_STORAGE', "file");

		switch (Str::lower($storage)) {
			case 'file':
				// $file = $request->file("file");
				$ext = $file->getClientOriginalExtension()?: "txt";
				$mime_type = $file->getMimeType()?:"plain/text";
				$path_directory = $file_directory;

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}
				
				$filename = Helper::create_filename($ext);

				$file->move($path_directory, $filename); 
				return [ 
					"path" => Str::lower($file_directory), 
					"directory" => Str::lower(URL::to($path_directory)), 
					"filename" => Str::lower($filename),
					"source" => Str::lower($storage),
					"type" => $mime_type
					
				];

			break;
			
			default:
				return array();
			break;
		}
	}
}