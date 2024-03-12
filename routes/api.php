<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Master\RoleController;
use App\Http\Controllers\Api\Master\DistrictTalukaVillageController;
use App\Http\Controllers\Api\Labour\LabourController;
use App\Http\Controllers\Api\Master\MasterController;
use App\Http\Controllers\Api\Master\ProjectController;
use App\Http\Controllers\Api\Labour\LabourFamilyDetailsController;
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
    Route::post('/add-role', [RoleController::class, 'add']);
    Route::post('/add-labour', [LabourController::class, 'add']);
    Route::get('/list-labour', [LabourController::class, 'getAllLabourList']);
    Route::get('/filter-labour-list', [LabourController::class, 'filterLabourList']);

    Route::post('/update-labour/{id}', [LabourController::class, 'updateParticularDataLabour']);
    Route::get('/states/{countryId}', [DistrictTalukaVillageController::class, 'getState']);
    Route::get('/district', [DistrictTalukaVillageController::class, 'getDistrict']);
    Route::get('/taluka/{districtId}', [DistrictTalukaVillageController::class, 'getTaluka']);
    Route::get('/village/{talukaId}', [DistrictTalukaVillageController::class, 'getVillage']);
    Route::get('/list-gender', [MasterController::class, 'getAllGender']);
    Route::get('/list-maritalstatus', [MasterController::class, 'getAllMaritalStatus']);
    Route::get('/list-skills', [MasterController::class, 'getAllSkill']);
    Route::get('/list-relation', [MasterController::class, 'getAllRelation']);
    Route::get('/list-project', [ProjectController::class, 'getAllProject']);
    Route::post('/add-family-details/{labour_id}', [LabourFamilyDetailsController::class, 'add']);
    
    Route::post('/logout', 'App\Http\Controllers\Api\Auth\AuthController@logout')->middleware('auth:sanctum');

});





