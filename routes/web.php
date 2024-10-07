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
    return view('auth.login');
});


Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');
Route::get('/data_sensors', function () {
    return view('pages.data_sensors');
});

Route::get('/action_history', function () {
    return view('pages.action_history');
});

Route::get('/profile', function () {
    return view('pages.profile');
});
