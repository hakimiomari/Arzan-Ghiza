<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessUser\CreateBussinessAccountRequest;
use App\Http\Requests\BusinessUser\LoginBUserRequest;
use App\Http\Requests\BusinessUser\StoreUserRequest;
use App\Http\Requests\BusinessUser\UpdateUserRequest;
use App\Http\Requests\User\ChangeAdminBussinessAndUserPassword;
use App\Http\Requests\User\ChangeUserPassword;
use App\Models\Address;
use App\Models\Bussiness;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Traits\HttpResponses as TraitsHttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Hash;

class BussinessAuthController extends Controller
{
    use ResponseTrait;
    use TraitsHttpResponses;

    public function login(LoginBUserRequest $request)
    {
        try {
            $user = Bussiness::where('email', '=', $request->input('email'))->firstOrFail();
            $role = $user->roles;
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['errors' => "Email or Password is Incorrect"], 400);
            }
            return $this->success([
                'id' => $user->id,
                'roles' => $role[0]->id,
                'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something went wrong',
                'errors' => $e->getMessage(),
            ], 401);
        }
    }

    public function register(StoreUserRequest $request)
    {
        $user = Bussiness::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'delivery' => $request->delivery,
            'has_user_account' => 0
        ])->assignRole('bussiness');
        $user->bussiness_name = $request->bussiness_name;
        $user->save();
        $role = $user->roles;
        $hasUserAccount = User::where('email', $request->email)->first();
        if ($hasUserAccount) {
            $hasUserAccount->has_bussiness_account = 1;
            $hasUserAccount->save();
            $user->has_user_account = 1;
            $user->save();
        }
        $address = Address::create([
            'bussinesses_id' => $user->id,
            'city' => $request->city,
            'district' => $request->district,
            'valige' => $request->valige,
        ]);
        $token = $user->createToken('Api Token of ' . $user->name)->plainTextToken;
        return $this->success([
            'id' => $user->id,
            'roles' => $role[0]->id,
            'token' => $token
        ]);
    }

    public function logout($id)
    {
        try {
            $user = Bussiness::findOrFail($id);

            $user->tokens()->delete();

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
    //get a business user data
    public function userData($id)
    {
        $user = Bussiness::with('products', 'address')->where('id', $id)->get();
        $order = Order::where('bussiness_id', $user[0]['id'])->get();
        $user[0]['photo'] = asset('images/bussiness/' . $user[0]['photo']);
        $user[0]['sales'] = $order;
        return response()->json([
            "user" => $user
        ]);
    }
    // get User Data
    public function getUserData($id)
    {
        try {
            $user = Bussiness::with(
                'address'
            )->where('id', $id)->get();
            $delivery = $user[0]->delivery;
            if ($delivery) {
                $user[0]['delivery'] = true;
            } else {
                $user[0]['delivery'] = false;
            }
            if ($user[0]->photo) {
                $user[0]['photo'] = asset('images/bussiness/' . $user[0]->photo);
            }
            return response()->json([
                "user" => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }

    // Update user Profile

    public function UpdateUserData(UpdateUserRequest $request)
    {
        try {
            $allData = $request->all();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $user = Bussiness::findOrFail($request->input('id'));
                if ($user) {
                    $oldImage = public_path('images/bussiness/' . $user->photo);
                    if ($user->photo) {
                        unlink($oldImage);
                    }
                    $image->move(public_path('images/bussiness'), $imageName);

                    if ($request->delivery == 'true') {
                        $delivery = 1;
                    } else {
                        $delivery = 0;
                    }
                    $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'delivery' => $delivery
                    ]);
                    $user->bussiness_name = $request->bussiness_name;
                    $user->photo = $imageName;
                    $user->save();
                    $address = Address::where('bussinesses_id', $request->id);
                    $address->update([
                        'city' => $request->city,
                        'district' => $request->district,
                        'valige' => $request->valige,
                    ]);
                }
                return response()->json(['message' => 'Account Successfully Apdated'], 200);
            } else {

                $user = Bussiness::findOrFail($request->input('id'));
                if ($request->delivery == 'true') {
                    $delivery = 1;
                } else {
                    $delivery = 0;
                }
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'delivery' => $delivery
                ]);
                $user->bussiness_name = $request->bussiness_name;
                $user->save();
                $address = Address::where('bussinesses_id', $request->id);
                $address->update([
                    'city' => $request->city,
                    'district' => $request->district,
                    'valige' => $request->valige,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }
    // change Password

    public function resetPassword(ChangeUserPassword $request)
    {
        try {
            $user = Bussiness::find($request->id);
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

    // change password by admin
    public function AdminResetPassword(ChangeAdminBussinessAndUserPassword $request)
    {
        try {
            $user = Bussiness::find($request->id);
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

    // delete User
    public function deleteUser($id)
    {
        try {
            $user = Bussiness::findOrFail($id);
            $SUser = User::where('email', $user->email)->first();
            if ($SUser) {
                $SUser->has_bussiness_account = 0;
                $SUser->save();
            }
            $image = asset('images/userImages', $user->image);
            if (file_exists($image)) {
                unlink($image);
            }
            $user->tokens()->delete();
            $address = Address::where('bussinesses_id', $id)->first();
            $address->delete();
            $products = $user->products;
            for ($i = 0; $i < $products->count(); $i++) {
                unlink('images/products/' . $products[$i]['image']);
            }
            $products = $user->products()->delete();
            $user->delete();
            return response()->json([
                'message' => 'Successfully deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'something went wrong'
            ]);
        }
    }
    // get all bussiness user
    public function AllUsers()
    {

        try {
            $users = Bussiness::with('products', 'address')->paginate(17);
            $i = 0;
            foreach ($users as $user) {
                unset($users[$i]['photo']);
                unset($users[$i]['email_verified_at']);
                $users[$i]['city'] = $user->address->city;
                $users[$i]['district'] = $user->address->district;
                $users[$i]['valige'] = $user->address->valige;
                unset($users[$i]['address']);
                $i++;
            }
            return response()->json([
                'users' => $users,
                'message' => 'all users'
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
    // ////////////////
    public function changeToUserAccount(Request $request)
    {
        try {
            $bUser = Bussiness::where('id', $request->id)->first();
            $user = User::where('email', $bUser->email)->first();
            $bUser->tokens()->delete();

            $role = $user->roles;
            return $this->success([
                'id' => $user->id,
                'roles' => $role[0]->id,
                'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
