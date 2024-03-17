<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/', function () {
//     return redirect('/login');
// });
Route::group(['namespace' => 'App\Http\Controllers'], function()
{  
    Route::get('/shop', 'controller_test@getAllShops');
    Route::get('/isi/{id}', 'controller_test@isiShops');

});

Route::get('/', function () {
    return redirect('/index');
});

Route::controller(AuthController::class)->group(function(){
    Route::post('/login', 'login')->name('login')->middleware('guest');
    Route::post('/index', 'getUserInfo')->name('getUserInfo')->middleware('auth:sanctum');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

Route::group([], function(){
    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect('/panel');
        } else {
            return view('log.login');
        }
    });

    Route::get('/index', function () {
        return view('index');
    });

    Route::get('/panel', function () {
        return view('panel');
    });
});

Route::any('{any}', function () {
    return view('errors.404');
})->where('any', '.*');

