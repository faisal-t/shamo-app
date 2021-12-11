<?php

use App\Http\Controllers\Api\ProductCategoryControler;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
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
Route::get('products',[ProductController::class,'all']);
Route::get('categories',[ProductCategoryControler::class,'all']);

Route::get('', [UserController::class,'register']);



Route::middleware('auth:sanctum')->group(function() {
    Route::get('user',[UserController::class,'fecth']);
    Route::post('user',[UserController::class,'updateProfile']);
    Route::post('logout',[UserController::class,'logout']);

    Route::get('transactions',[TransactionController::class,'all']);
    Route::post('checkout',[TransactionController::class,'checkout']);
    
});