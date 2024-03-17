<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Master\RoleController;
use App\Http\Controllers\Api\Master\DistrictTalukaVillageController;
use App\Http\Controllers\Api\Labour\LabourController;
use App\Http\Controllers\Api\Master\MasterController;
use App\Http\Controllers\Api\Master\ProjectController;
use App\Http\Controllers\Api\Master\AllMasterController;
use App\Http\Controllers\Api\Labour\LabourFamilyDetailsController;
use App\Http\Controllers\Api\Labour\LabourAttendanceMarkController;

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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route::post('/list-gender', [MasterController::class, 'getAllGender']);
// Route::post('/list-maritalstatus', [MasterController::class, 'getAllMaritalStatus']);
// Route::post('/list-skills', [MasterController::class, 'getAllSkill']);
// Route::post('/list-relation', [MasterController::class, 'getAllRelation']);
// Route::post('/list-document', [MasterController::class, 'getAllDocument']);
Route::post('/list-masters', [AllMasterController::class, 'getAllMasters']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {


    // Protected routes that require authentication token
    Route::middleware('auth:api')->group(function () {
        Route::post('/add-labour', [LabourController::class, 'add']);
        Route::post('/list-labour', [LabourController::class, 'getAllLabourList']);
        Route::get('/filter-labour-list', [LabourController::class, 'filterLabourList']);
        Route::post('/update-labour/{id}', [LabourController::class, 'updateParticularDataLabour']);
        Route::get('/states/{countryId}', [DistrictTalukaVillageController::class, 'getState']);
        Route::get('/district', [DistrictTalukaVillageController::class, 'getDistrict']);
        Route::get('/taluka/{districtId}', [DistrictTalukaVillageController::class, 'getTaluka']);
        Route::get('/village/{talukaId}', [DistrictTalukaVillageController::class, 'getVillage']);
        Route::post('/list-project', [ProjectController::class, 'getAllProject']);
        Route::post('/list-user-labours', [LabourController::class, 'getAllUserLabourList']);
        Route::post('/particular-project-labours/{project_id}', [ProjectController::class, 'ProjectLaboursList']);
        Route::post('/filter-mgnrega-id-labour-list', [LabourController::class, 'filtermgnregaIdLabourList']);
        Route::post('/filter-project-labour-list', [ProjectController::class, 'filterData']);
        Route::post('/add-family-details/{labour_id}', [LabourFamilyDetailsController::class, 'add']);
        
        Route::post('/add-attendance-mark', [LabourAttendanceMarkController::class, 'addAttendanceMark']);
        Route::post('/list-attendance-marked', [LabourAttendanceMarkController::class, 'getAllAttendanceMarkedLabour']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});






