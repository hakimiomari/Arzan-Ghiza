<?php

use App\Mail\PurchesMail;
use App\Mail\SaleMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/mail', function () {
    $data = Cache::get('purchase_data');
    $value = Cache::get('user');
    Mail::to($value['email'])->send(new PurchesMail());
    Mail::to($data['bussiness_user'])->send(new SaleMail());
});


Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';
