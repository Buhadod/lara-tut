<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
		
        $input =$request->only('picture','bio'); 
        

        //different validation for file
        // 'photo' => 'file|mimes:jpeg,bmp,png'
        // 'report' => 'file|mimes:pdf'

       
        $validator = Validator::make($input, [
            'picture' => 'required|image|max:2048|',
            'bio' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }
        
        //Get file path
        $file_path = $input['picture']->path();

        //Get Extension
        $extension = $input['picture']->extension();

        //Create new file name
        $newfilename = uniqid().".". $extension;

        $upload_status = (move_uploaded_file($file_path,$newfilename));

        if($upload_status)
        {
            return "File uploaded sucessfully with name".$newfilename;
            //TODO: save file name and name in the database
        }
        else{
            return "File to upload a file";
        }
       

    }
}
