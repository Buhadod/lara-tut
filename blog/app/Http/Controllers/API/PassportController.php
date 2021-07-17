<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class PassportController extends Controller
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

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    }

    
}
