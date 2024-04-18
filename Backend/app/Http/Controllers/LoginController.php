<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function submit(Request $request) {
        // return "oishfshf";
        $request->validate([
            'phone' => 'required|min:10'
        ]);


        // Validator::make($request->all(), [
        //     'phone' => 'required|numeric|digits:10',
        // ])->validate();

        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if(!$user) {
            return response()->json([
                'message'=> "Unavailable User with that phone number"
            ], 401);
        }

        $user->notify(new LoginNeedVerification());

        return response()->json(['message' => 'OTP has been sent to User']);
    }

    public function verify(Request $request) {
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'numeric|required'
        ]);

        $user = User::where(['phone' => $request->phone])->where(['login_code'=>$request->login_code])->first();

        if($user) {
            $user->update([
                'login_code' => null
            ]);
            return $user->createToken($request->login_code)->plainTextToken;
        }

        return response()->json([
            'message'=> 'Invalid user OTP code!'
        ], 402);
    }
}
