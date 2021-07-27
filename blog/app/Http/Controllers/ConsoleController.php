<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;

class ConsoleController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->only('name','email','password');
        
        $validator = Validator::make($input, [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        $input['password'] = bcrypt($request->password);

        
        $user = User::create($input);

        //$user->id

        $data = [];
        $data['user_id'] = $user->id;
        $data['name'] = $user->name;

        $profile = Profile::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([ 'user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $input = $request->only('email','password');
        
        $validator = Validator::make($input, [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        if (!auth()->attempt($input)) {
            return response(['message' => 'Invalid Credentials'],422);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        $profile =  auth()->user()->profile;
        //$profile =  Profile::where('user_id',auth()->user()->id)->get();

        return response(['user' => auth()->user(),'profile'=>$profile, 'access_token' => $accessToken]);

    }

    public function getUser(Request $request)
    {
        $user = auth()->user();

        return $user;
    }

    public function show($id,Request $request){

        //dd($id,$request->all());
        return Product::find($id);
    }
}
