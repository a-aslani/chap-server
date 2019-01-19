<?php

namespace App\Http\Controllers\Api\v1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
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

            $validTime = 3 * 60;

            if($user->updated_at == null) {

                $totalTime = Carbon::now()->timestamp - $user->created_at->timestamp;

                if($totalTime > $validTime) {
                    return response()->json([
                        "data" => [
                            "message" => "مدت زمان اعتبار کد تایید شما به پایان رسیده است"
                        ],
                        "state" => false
                    ]);
                }

            } else {

                $totalTime = Carbon::now()->timestamp - $user->updated_at->timestamp;
                if($totalTime > $validTime) {
                    return response()->json([
                        "data" => [
                            "message" => "مدت زمان اعتبار کد تایید شما به پایان رسیده است"
                        ],
                        "state" => false
                    ]);
                }
            }

            $token = Auth('api')->login($user);

            return response()->json([
                'data' => [
                    'api_token' => $token,
                    'has_password' => $user->password != null,
                    'is_new_user' => !$user->name
                ],
                "state" => true
            ]);
        }

        return response()->json([
            'data' => [
                'message' => 'کد تایید نامعتبر است'
            ]
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function recode($phoneNumber) {

        $user = User::where('phone_number', $phoneNumber)->first();

        $verify_code = rand(11111, 99999);

        $user->update([
            'verify_code' => $verify_code
        ]);

        //TODO: send sms verify code

        return response()->json([
            'data' => [
                'phone_number' => $user->phone_number,
            ]
        ]);

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

    public function user() {

        $user = Auth('api')->user();

        return new \App\Http\Resources\User($user);
    }
}
