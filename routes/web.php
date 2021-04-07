<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/amo', [\App\Http\Controllers\AmoController::class, 'index'])->name('amo');
Route::get('/amo/app', [\App\Http\Controllers\AmoController::class, 'amoApp'])->name('amo_app');
Route::get('/amo/auth', [\App\Http\Controllers\AmoController::class, 'amoAuth'])->name('amo_auth');
