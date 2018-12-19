<?php

namespace App\Http\Controllers\Api\v1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function start(Request $request) {

        $this->validate($request, [
            'phone_number' => ['required', 'min:10', 'max:10']
        ]);

        $phone_number = $request->phone_number;

        $user = User::where('phone_number', $phone_number)->first();

        $verify_code = rand(11111, 99999);

        if(!$user) {

            User::create([
                'phone_number' => $phone_number,
                'verify_code' => $verify_code
            ]);

            //TODO: send sms verify code

            return response()->json([
                'data' => [
                    'phone_number' => $phone_number,
                ]
            ]);
        }

        $user->update([
            'verify_code' => $verify_code
        ]);

        //TODO: send sms verify code

        return response()->json([
            'data' => [
                'phone_number' => $phone_number
            ]
        ]);
    }

    public function verify(Request $request, $phoneNumber) {

        $this->validate($request, [
            'verify_code' => ['required', 'min:5', 'max:5']
        ]);

        $user = User::where('phone_number', $phoneNumber)->first();

        if($user->verify_code === $request->verify_code) {

            $token = Auth('api')->login($user);

            return response()->json([
                'data' => [
                    'api_token' => $token,
                    'has_password' => $user->password != null,
                    'is_new_user' => !$user->name
                ]
            ]);
        }

        return response()->json([
            'data' => [
                'messages' => 'کد فعالسازی نامعتبر است'
            ]
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function register(Request $request) {

        $this->validate($request, [
            'name' => ['required'],
            'family' => ['required']
        ]);

        Auth('api')->user()->update([
            'name' => $request->name,
            'family' => $request->family
        ]);

        return response()->json([
            'data' => [
                'registered' => true
            ]
        ]);
    }

    public function checkPassword(Request $request) {

        $this->validate($request, [
            'password' => ['required']
        ]);

        if(Hash::check($request->password, Auth('api')->user()->password)) {
            return response()->json([
                'data' => [
                    'verify_password' => true
                ]
            ]);
        }

        return response()->json([
            'data' => [
                'verify_password' => false
            ]
        ], Response::HTTP_UNAUTHORIZED);
    }
}
