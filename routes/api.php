<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Master\RoleController;
use App\Http\Controllers\Api\Master\DistrictTalukaVillageController;
use App\Http\Controllers\Api\Labour\LabourController;
use App\Http\Controllers\Api\Master\MasterController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/add-role', [RoleController::class, 'add']);
    Route::post('auth/add-labour', [LabourController::class, 'add']);
    Route::get('auth/states/{countryId}', [DistrictTalukaVillageController::class, 'getState']);
    Route::get('auth/district/{stateId}', [DistrictTalukaVillageController::class, 'getDistrict']);
    Route::get('auth/taluka/{districtId}', [DistrictTalukaVillageController::class, 'getTaluka']);
    Route::get('auth/village/{talukaId}', [DistrictTalukaVillageController::class, 'getVillage']);
    Route::get('auth/list-gender', [MasterController::class, 'getAllGender']);
    Route::get('auth/list-maritalstatus', [MasterController::class, 'getAllMaritalStatus']);

    Route::post('/logout', 'App\Http\Controllers\Api\Auth\AuthController@logout')->middleware('auth:sanctum');

    // Add more routes here that require authentication via Sanctum middleware
});





