<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MqttController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::get('/action_history', [ActionController::class, 'getAllAction'])->name('getAllAction');
// Route::get('/mqtt/receive', [MqttController::class, 'receive']);

Route::post('/', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/profile', [UserController::class, 'profile']);
Route::get('/latest-data', [DataController::class, 'getLatestData']);
Route::get('/latest-10-data', [DataController::class, 'get10LatestData']);
Route::get('/data_sensors', [DataController::class, 'getAllData'])->name('data.getAllData');

Route::get('/action_history', [ActionController::class, 'getAllAction'])->name('action.getAllAction');
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

Route::post('/toggle-fan', [ActionController::class, 'toggleFan']);
Route::post('/toggle-ac', [ActionController::class, 'toggleAC']);
Route::post('/toggle-light', [ActionController::class, 'toggleLight']);
Route::post('/toggle-cook', [ActionController::class, 'toggleCook']);
Route::post('/alter', [ActionController::class, 'alter']);
Route::get('/getCB', [ActionController::class, 'getCB']);
Route::get('/get-mqtt-data', [ActionController::class, 'getMqttData']);
Route::get('/get-ac-status', [ActionController::class, 'getACStatus']);
Route::get('/get-fan-status', [ActionController::class, 'getFanStatus']);
Route::get('/get-light-status', [ActionController::class, 'getLightStatus']);
Route::get('/get-cook-status', [ActionController::class, 'getCookStatus']);

// test
Route::get('/get-dust', [DataController::class, 'getLatestDust']);
