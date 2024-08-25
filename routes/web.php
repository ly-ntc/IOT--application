<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    return view('auth.login');
});
Route::post('/', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    });

    Route::get('/data_sensors', function () {
        return view('pages.data_sensors');
    });

    Route::get('/action_history', function () {
        return view('pages.action_history');
    });

    Route::get('/profile', function () {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    });

    Route::get('/latest-data', [DataController::class, 'getLatestData']);

});
