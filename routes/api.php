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
use App\Http\Controllers\Api\Labour\LabourAttendanceMarkController;
use App\Http\Controllers\Api\Documents\GramPanchayatDocumentController;
use App\Http\Controllers\Api\Labour\OfficerController;

use App\Http\Controllers\Api\Labour\AttendanceMarkVisibleForOfficerController;




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
Route::post('/list-masters', [AllMasterController::class, 'getAllMasters']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {


    // Protected routes that require authentication token
    Route::middleware('auth:api')->group(function () {
        Route::post('/add-labour', [LabourController::class, 'add']);
        Route::post('/list-labour', [LabourController::class, 'getAllLabourList']);
        Route::post('/list-user-labours', [LabourController::class, 'getAllUserLabourList']);
        Route::post('/particular-labour-details', [LabourController::class, 'getParticularLabour']);

        Route::post('/filter-project-labour-list', [ProjectController::class, 'filterDataProjectsLaboursMap']);
        Route::post('/list-project', [ProjectController::class, 'getAllProject']);
        Route::post('/add-attendance-mark', [LabourAttendanceMarkController::class, 'addAttendanceMark']);
        Route::post('/list-attendance-marked', [LabourAttendanceMarkController::class, 'getAllAttendanceMarkedLabour']);

        // Route::post('/update-attendance-mark/{id}', [LabourAttendanceMarkController::class, 'updateAttendanceMark']);
        Route::post('/update-attendance-mark', [LabourAttendanceMarkController::class, 'updateAttendanceMark']);
        Route::get('/filter-labour-list', [LabourController::class, 'filterLabourList']);

        Route::post('/add-document', [GramPanchayatDocumentController::class, 'add']);
        Route::post('/list-document', [GramPanchayatDocumentController::class, 'getAllDocuments']);
        Route::post('/update-document', [GramPanchayatDocumentController::class, 'updateDocuments']);
        Route::post('/download-document', [GramPanchayatDocumentController::class, 'getDownloadDocument']);
        

        Route::post('/list-document-officer', [GramPanchayatDocumentController::class, 'getAllDocumentsOfficer']);
        
        Route::post('/update-labour/{id}', [LabourController::class, 'updateParticularDataLabour']);
        Route::get('/states/{countryId}', [DistrictTalukaVillageController::class, 'getState']);
        Route::get('/district', [DistrictTalukaVillageController::class, 'getDistrict']);
        Route::get('/taluka/{districtId}', [DistrictTalukaVillageController::class, 'getTaluka']);
        Route::get('/village/{talukaId}', [DistrictTalukaVillageController::class, 'getVillage']);

        Route::post('/list-send-approved-labour', [LabourController::class, 'getSendApprovedLabourList']);
        Route::post('/list-approved-labour', [LabourController::class, 'getApprovedLabourList']);
        Route::post('/list-not-approved-labour', [LabourController::class, 'getNotApprovedLabourList']);
        Route::post('/list-labour-rejected', [LabourController::class, 'getRejectedLabourList']);

        Route::post('/update-labour-status-approved', [LabourController::class, 'updateLabourStatusApproved']);
        Route::post('/update-labour-status-not-approved', [LabourController::class, 'updateLabourStatusNotApproved']);
      
        Route::post('/list-labour-received-to-officer-for-approval', [OfficerController::class, 'getSendApprovedLabourListOfficer']);
        Route::post('/list-labour-approved-by-officer', [OfficerController::class, 'getApprovedLabourListOfficer']);
        Route::post('/list-labour-not-approved-by-officer', [OfficerController::class, 'getNotApprovedLabourListOfficer']);
        Route::post('/list-labour-rejected-by-officer', [OfficerController::class, 'getRejectedLabourListOfficer']);
        

        Route::post('/update-officer-labour-status-approved', [OfficerController::class, 'updateLabourStatusApproved']);
        Route::post('/update-officer-labour-status-not-approved', [OfficerController::class, 'updateLabourStatusNotApproved']);
        Route::post('/update-officer-labour-status-rejected', [OfficerController::class, 'updateLabourStatusRejected']);

        
        Route::post('/officer-count-labour', [OfficerController::class, 'countOfficerLabour']);
        Route::post('/list-particular-officer-labour-details', [OfficerController::class, 'getParticularLabourOfficer']);

        Route::post('/project-list-lat-log', [ProjectController::class, 'getAllProjectLatLong']);
        
       
        Route::post('/update-labour-first-form', [LabourController::class, 'updateLabourFirstForm']);
        Route::post('/update-labour-second-form', [LabourController::class, 'updateLabourSecondForm']);
        Route::post('/particular-labour-details-for-update', [LabourController::class, 'getParticularLabourForUpdate']);
       
        Route::post('/list-attendance-marked-visible-for-officer', [AttendanceMarkVisibleForOfficerController::class, 'getAllAttendanceMarkedLabour']);
        Route::post('/project-list-for-officer', [AttendanceMarkVisibleForOfficerController::class, 'getAllProjectListForOfficer']);

        
        
        Route::post('logout', [AuthController::class, 'logout']);
    });
});






