<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnologyController;
use App\Http\Middleware\CheckAuth;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group([
    'prefix' => 'auth'
],function(){
    Route::post('/register',[AuthController::class,'register'])->name('register');
    Route::post('/login',[AuthController::class,'login'])->name('login');
});


/**
 * Routes api for
 */
Route::group([
    'middleware' => 'checkAuth',
    'prefix' => 'auth',
],function(){
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/profile',[AuthController::class,'profile'])->name('profile');
});

Route::middleware(CheckAuth::class)->prefix('service')->group(function() {
    Route::post('/', [ServiceController::class, 'saveService']);
    Route::get('/', [ServiceController::class, 'getListServices']);
    Route::get('/{id}', [ServiceController::class, 'getOneService']);
    Route::put('/{id}', [ServiceController::class, 'updateService']);
    Route::delete('/{id}', [ServiceController::class, 'deleteService']);
});

Route::middleware(CheckAuth::class)->prefix('technology')->group(function() {
    Route::post('/', [TechnologyController::class, 'saveTechnology']);
    Route::get('/', [TechnologyController::class, 'getListTechnologies']);
    Route::get('/{id}', [TechnologyController::class, 'getOneTechnology']);
    Route::put('/{id}', [TechnologyController::class, 'updateTechnology']);
    Route::delete('/{id}', [TechnologyController::class, 'deleteTechnology']);
});
