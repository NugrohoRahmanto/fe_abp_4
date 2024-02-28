<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::any('{any}', function () {
    // return view('error.404');
    return response()->json([
        'status' => 'error',
        'message' => 'Page not found',
    ])->setStatusCode(404);
})->where('any', '.*');
