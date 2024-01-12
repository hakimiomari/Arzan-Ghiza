<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessUser\CreateBussinessAccountRequest;
use App\Http\Requests\User\ChangeAdminBussinessAndUserPassword;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\ResponseTrait;
use App\Http\Requests\User\ChangeUserPassword;
use App\Http\Requests\User\UpdateUser;
use App\Models\Address;
use App\Models\Bussiness;
use App\Traits\HttpResponses as TraitsHttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    use ResponseTrait;
    use TraitsHttpResponses;

    public function register(RegisterUser $request)
    {
        try {
            Cache::forget('user');
            Cache::forget('product_data');
            Cache::forget('purchase_data');
            $data = $request->all();
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'city' => $request->input('city'),
                'district' => $request->input('district'),
                'valige' => $request->input('valige'),
                'has_bussiness_account' => 0
            ])->assignRole('user');
            $token = $user->createToken('user_token')->plainTextToken;
            $role = $user->roles;
            $data = [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_name' => $user->name,
            ];
            $bussiness = Bussiness::where('email', $user->email)->first();
            if ($bussiness) {
                $user->has_bussiness_account = 1;
                $user->save();
                $bussiness->has_user_account = 1;
                $bussiness->save();
            }
            Cache::add('user', $data, 60 * 60 * 24 * 365);
            $response = [
                'id' => $user->id,
                'token' => $token,
                'roles' => $role[0]->id
            ];

            return $this->success($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something went wrong',
                'errors' => $e->getMessage(),
            ]);
        }
    }
    // create bussiness account
    public function createBussinessAccount(CreateBussinessAccountRequest $request)
    {
        Cache::forget('user');
        Cache::forget('product_data');
        Cache::forget('purchase_data');
        $incomingData = $request->all();
        $user = User::where('id', $request->user_id)->first();
        $bussiness = Bussiness::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make($request->password),
            'phone' => $user->phone,
            'delivery' => $request->delivery,
            "has_user_account" => 1,
        ])->assignRole('bussiness');
        $bussiness->bussiness_name = $request->bussiness_name;
        $bussiness->save();
        $user->has_bussiness_account = 1;
        $user->save();
        $role = $bussiness->roles;
        $address = Address::create([
            'bussinesses_id' => $bussiness->id,
            'city' => $user->city,
            'district' => $user->district,
            'valige' => $user->valige,
        ]);
        $token = $bussiness->createToken('Api Token of ' . $bussiness->name)->plainTextToken;
        return $this->success([
            'id' => $bussiness->id,
            'roles' => $role[0]->id,
            'token' => $token
        ]);
    }

    // login method
    public function login(LoginUserRequest $request)
    {
        try {
            Cache::forget('user');
            Cache::forget('product_data');
            Cache::forget('purchase_data');
            $user = User::where('email', '=', $request->input('email'))->firstOrFail();
            $role = $user->roles;
            if (Hash::check($request->input('password'), $user->password)) {
                $token = $user->createToken('user_token')->plainTextToken;
                $data = [
                    'user_id' => $user->id,
                    'email' => $request->input('email'),
                    'user_name' => $user->name,
                ];
                Cache::add('user', $data, 60 * 60 * 24 * 365);
                $response = [
                    'id' => $user->id,
                    'token' => $token,
                    'roles' => $role[0]->id
                ];
                return $this->success($response);
            } else {
                return response()->json(['errors' => "Email or Password is Incorrect"], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'something went wrong'
            ], 401);
        }
    }
    // logout method
    public function logout($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->tokens()->delete();
            Cache::forget('user');
            Cache::forget('product_data');
            Cache::forget('purchase_data');
            return response()->json(
                'User Logged Out',
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // update the profile
    public function update(UpdateUser $request)
    {
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $userModel = User::findOrFail($request->input('id'));
                if ($userModel) {
                    $oldImage = public_path('images/userImages/' . $userModel->photo);
                    if ($userModel->photo) {
                        unlink($oldImage);
                    }
                    $image->move(public_path('images/userImages'), $imageName);
                    $userModel->photo = $imageName;
                    $userModel->name = $request->input('name');
                    $userModel->email = $request->input('email');
                    $userModel->phone = $request->input('phone');
                    $userModel->city = $request->input('city');
                    $userModel->district = $request->input('district');
                    $userModel->valige = $request->input('valige');
                    $userModel->update();
                    return response()->json([
                        'message' => "Profile successfully updated",
                    ]);
                }
            } else {
                $userModel = User::findOrFail($request->input('id'));
                if ($userModel) {
                    $userModel->name = $request->input('name');
                    $userModel->email = $request->input('email');
                    $userModel->phone = $request->input('phone');
                    $userModel->city = $request->input('city');
                    $userModel->district = $request->input('district');
                    $userModel->valige = $request->input('valige');
                    $userModel->update();
                    return response()->json([
                        'message' => "Profile successfully updated",
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // get functionality

    public function getUserData($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User Not found'], 404);
        } else {
            if ($user->photo) {
                $user['image'] = asset('images/userImages/' . $user->photo);
                return response()->json([
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'user' => $user,
                ]);
            }
        }
    }

    /**
     *       @param \App\Http\Requests\updateProfileRequest request
     *       @param int id
     *       @return \Illuminate\Http\Response
     */

    public function resetPassword(ChangeUserPassword $request)
    {
        try {
            $user = User::find($request->id);
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->update();
                return response()->json(['message' => 'Password Successfully changed']);
            } else {
                return response()->json(['errors' => ['old_password' => ["old password is incorrect"]]], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // reset password by admin
    public function AdminUserResetPassword(ChangeAdminBussinessAndUserPassword $request)
    {
        try {
            $user = User::find($request->id);
            $user->password = Hash::make($request->password);
            $user->update();
            return response()->json(['message' => 'Password Successfully changed']);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    /**
     *       @param int id
     *       @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $bussiness = Bussiness::where('email', $user->email)->first();
            if ($bussiness) {
                $bussiness->has_user_account = 0;
                $bussiness->save();
            }
            $image = asset('images/products', $user->image);
            if (file_exists($image)) {
                unlink($image);
            }
            $user->tokens()->delete();
            $user->delete();
            return response()->json('success');
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // change to bussiness account

    public function changeToBussinessAccount(Request $request)
    {
        try {
            $bUser = Bussiness::where('email', $request->email)->first();
            $user = User::where('email', $request->email)->first();
            $user->tokens()->delete();

            $role = $bUser->roles;
            return $this->success([
                'id' => $bUser->id,
                'roles' => $role[0]->id,
                'token' => $bUser->createToken('Api Token of ' . $bUser->name)->plainTextToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
