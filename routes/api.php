<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\UserController;
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
Route::post('admin/login', [AdminController::class, 'login']);
Route::post('admin/register', [AdminController::class, 'register']);
ROute::get('admin/logout', [AdminController::class, 'logout']);

Route::post('user/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('admin/logout', [AdminController::class, 'logout']);
    Route::get('admin/restaurant', [AdminController::class, 'restaurantId']);
    Route::get('admin/users/all', [AdminController::class, 'users']);
    Route::get('admin/tables/all', [AdminController::class, 'tables']);
    Route::post('admin/table/create', [AdminController::class, 'tableCreate']);
    Route::post('admin/restaurant/create', [AdminController::class, 'restaurantCreate']);

    Route::get('manager/user', [ManagerController::class, 'user']);
    Route::get('manager/users/all', [ManagerController::class, 'usersAll']);
    Route::get('manager/tables/all', [ManagerController::class, 'tablesAll']);
    Route::post('manager/resustab', [ManagerController::class, 'resUsTab']);
    Route::post('manager/resustab/create', [ManagerController::class, 'resUsTabCreate']);
    Route::put('manager/resustab/update', [ManagerController::class, 'resUsTabUpdate']);
    Route::post('manager/user/create', [ManagerController::class, 'userCreate']);
    Route::delete('manager/user/delete/{id}', [ManagerController::class, 'userDelete']);

    Route::get('user/logout', [UserController::class, 'logout']);
    Route::post('shema/user', [UserController::class, 'shemaUser']);
});
