<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\BussinessAuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\VerifyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// public routes
// login and registration routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/bussiness/register', [BussinessAuthController::class, 'register']);
Route::post('/bussiness/login', [BussinessAuthController::class, 'login']);
Route::post('/forget/password', [VerifyController::class, 'forgetPassword']);
Route::post('/verify', [VerifyController::class, 'verify']);
Route::post('/changePassword', [VerifyController::class, 'newPassword']);
Route::post('/search', [ProductController::class, 'search']);

// product
Route::get('/products/product/details/{id}', [ProductController::class, 'singleProductDetails']);
Route::get('/bussiness/products', [ProductController::class, 'products']);

// admin
Route::middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('admin/products', [AdminController::class, 'index']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/sales', [AdminController::class, 'sales']);
    Route::get('/admin/chart', [AdminController::class, 'chart']);
    Route::get('/admin/bussiness/users', [BussinessAuthController::class, 'AllUsers']);
    Route::post('admin/bussiness/user/reset-password', [BussinessAuthController::class, 'AdminResetPassword']);
    Route::post('/admin/user/reset-password', [AuthController::class, 'AdminUserResetPassword']);
});

// bussiness role routes

// bussiness/admin
Route::middleware('auth:sanctum', 'role:bussiness|admin')->group(function () {
    Route::post('bussiness/store', [ProductController::class, 'store']);
    Route::get('bussiness/logout/{id}', [BussinessAuthController::class, 'logout']);
    Route::get('bussiness/user/delete/{id}', [BussinessAuthController::class, 'deleteUser']);
    Route::get('bussiness/user/{id}', [BussinessAuthController::class, 'userData']);
    Route::get('bussiness/user/getData/{id}', [BussinessAuthController::class, 'getUserData']);
    Route::post('bussiness/user/updateUser', [BussinessAuthController::class, 'UpdateUserData']);
    Route::post('bussiness/user/reset-password', [BussinessAuthController::class, 'resetPassword']);
    Route::post('/bussiness/changeTUserAccount', [BussinessAuthController::class, 'changeToUserAccount']);
    // products
    Route::get('bussiness/products/{id}', [ProductController::class, 'userProduct']);
    Route::get('bussiness/products/product/{id}', [ProductController::class, 'getSingleProduct']);
    Route::get('bussiness/product/delete/{id}', [ProductController::class, 'deleteProduct']);
    Route::post('bussiness/product/update', [ProductController::class, 'updateSingleProduct']);
    Route::get('bussiness/sales/{id}', [ProductController::class, 'BSale']);
    Route::get('/bussiness/chart/{id}', [ProductController::class, 'chart']);
});

// admin/user
Route::middleware('auth:sanctum', 'role:admin|user')->group(function () {
    Route::get('/logout/{id}', [AuthController::class, 'logout']);
    Route::get('/user/delete/{id}', [AuthController::class, 'delete']);
    Route::get('/user/getData/{id}', [AuthController::class, 'getUserData']);
    Route::post('/user/updateUser', [AuthController::class, 'update']);
    Route::post('/user/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/getSession', [ProductController::class, 'getSession']);
    Route::get('/saving-order-data', [ProductController::class, 'successCheckout']);
    Route::post('/products/check_product', [ProductController::class, 'checkQuantity']);
    Route::post('/user/changeToBussinessAccount', [AuthController::class, 'changeToBussinessAccount']);
    Route::post('/create-new-account', [AuthController::class, 'createBussinessAccount']);

    Route::post('/payment', [PaymentController::class, 'store']);
});
