<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessUser\VerificationRequest;
use App\Http\Requests\User\ChangeAdminBussinessAndUserPassword;
use App\Mail\VerificationMail;
use App\Models\Bussiness;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\ResponseTrait;
use App\Traits\HttpResponses as TraitsHttpResponses;

class VerifyController extends Controller
{
    use ResponseTrait;
    use TraitsHttpResponses;
    public function verify(VerificationRequest $request)
    {
        try {

            $data = $request->all();
            $cacheData = Cache::get('timeStamp');
            if ($cacheData) {
                if ($cacheData['time']->isPast()) {
                    return response()->json([
                        'errors' => ['otp' => ["Your token is expired"]],
                    ], 422);
                }
                if ($cacheData['time']->isFuture()) {
                    if ($request->otp == $cacheData['random_number']) {
                        return response()->json(['message' => 'success']);
                    } else {
                        return response()->json([
                            'errors' => ['otp' => ["otp not match"]],
                        ], 422);
                    }
                }
            } else {
                return response()->json([
                    'errors' => ['otp' => ["Please Enter Your Email first in forget password"]],
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ]);
        }
    }

    // forget password
    public function forgetPassword(Request $request)
    {
        $requstData = $request->all();
        Cache::forget('timeStamp');
        $validator = Validator::make($request->all(), ['email' => 'required|email']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();
        $bussiness = Bussiness::where('email', $request->email)->first();
        $randomNumber = mt_rand(100000, 999999);
        $currentTime = Carbon::now()->setTimezone('Asia/Kabul');
        $newTime = $currentTime->copy()->addMinutes(2);
        if ($request->bussiness == true && $bussiness) {
            $data = [
                'time' => $newTime,
                'random_number' => $randomNumber,
                'email' => $request->email,
                'model' => "Bussiness"
            ];
        } else if ($request->bussiness == false && $user) {
            $data = [
                'time' => $newTime,
                'random_number' => $randomNumber,
                'email' => $request->email,
                'model' => "User"
            ];
        } else {
            return response()->json(['errors' => ['email' => ['Email is not exist']]], 404);
        }
        Cache::add('timeStamp', $data, 60 * 60);
        Mail::to($request->email)->send(new VerificationMail());

        return response()->json(["success" => "message"]);
    }

    // change password
    public function newPassword(ChangeAdminBussinessAndUserPassword $request)
    {
        $cacheData = Cache::get('timeStamp');
        if ($cacheData) {
            if ($cacheData['model'] == "User") {
                $user = User::where('email', $cacheData['email'])->first();
            } else {
                $user = Bussiness::where('email', $cacheData['email'])->first();
            }
            $user->password = Hash::make($request->password);
            $user->save();
            $role = $user->roles;
            $token = $user->createToken('Api Token of ' . $user->name)->plainTextToken;
            return $this->success([
                'id' => $user->id,
                'roles' => $role[0]->id,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'errors' => ['email' => "Please Enter Your Email first in forget password"],
            ], 422);
        }
    }
}
