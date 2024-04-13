<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Labour\LabourController;
use App\Http\Controllers\Api\Master\ProjectController;
use App\Http\Controllers\Api\Master\AllMasterController;
use App\Http\Controllers\Api\Labour\LabourAttendanceMarkController;
use App\Http\Controllers\Api\Documents\GramPanchayatDocumentController;
use App\Http\Controllers\Api\Documents\OfficerGramDocAppNotAppController;
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
// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/list-masters', [AllMasterController::class, 'getAllMasters']);
Route::post('/list-updated-master', [AllMasterController::class, 'getAllMastersUpdated']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {


    // Protected routes that require authentication token
    Route::middleware('auth:api')->group(function () {
         //=============Start labour=================
        Route::post('/add-labour', [LabourController::class, 'add']);
        Route::post('/list-labour', [LabourController::class, 'getAllLabourList']);
        Route::post('/update-labour-first-form', [LabourController::class, 'updateLabourFirstForm']);
        Route::post('/update-labour-second-form', [LabourController::class, 'updateLabourSecondForm']);
        Route::post('/autosugg-mgnrega-card-id', [LabourController::class, 'autoSuggMgnregaCardId']);
        Route::post('/gramsevak-reports-count', [LabourController::class, 'gramsevakReportscount']);
        Route::post('/mgnregacardid-alreadyexist', [LabourController::class, 'mgnregaCardIdAlreadyExist']);

        //=============Start ProjectController=================
        Route::post('/filter-project-labour-list', [ProjectController::class, 'filterDataProjectsLaboursMap']);
        Route::post('/list-project-for-officer', [ProjectController::class, 'getAllProjectForOfficer']);
        Route::post('/project-list-lat-log', [ProjectController::class, 'getAllProjectLatLong']);

        
        // ============Start LabourAttendanceMarkController============
        Route::post('/add-attendance-mark', [LabourAttendanceMarkController::class, 'addAttendanceMark']);
        Route::post('/list-attendance-marked', [LabourAttendanceMarkController::class, 'getAllAttendanceMarkedLabour']);
        Route::post('/update-attendance-mark', [LabourAttendanceMarkController::class, 'updateAttendanceMark']);
        
        //  ===================Start GramPanchayatDocumentController============
        Route::post('/add-document', [GramPanchayatDocumentController::class, 'add']);
        Route::post('/list-document', [GramPanchayatDocumentController::class, 'getAllDocuments']);
        Route::post('/update-document', [GramPanchayatDocumentController::class, 'updateDocuments']);
        Route::post('/download-document', [GramPanchayatDocumentController::class, 'getDownloadDocument']);
        Route::post('/list-document-officer', [GramPanchayatDocumentController::class, 'getAllDocumentsOfficer']);
        Route::post('/count-gramsevak-document', [GramPanchayatDocumentController::class, 'countGramsevakDocument']);
         
        //  ===================Start OfficerGramDocAppNotAppController============
        Route::post('/received-doc-list-for-app-notapp', [OfficerGramDocAppNotAppController::class, 'getReceivedDocumentListForAppNotApp']);
        Route::post('/update-officer-document-status-approved', [OfficerGramDocAppNotAppController::class, 'updateDocumentStatusApproved']);
        Route::post('/update-officer-document-status-not-approved', [OfficerGramDocAppNotAppController::class, 'updateDocumentStatusNotApproved']);
        Route::post('/count-officer-document', [OfficerGramDocAppNotAppController::class, 'countOfficerDocument']);

        //    =================Start OfficerController===============
        Route::post('/list-labour-received-to-officer-for-approval', [OfficerController::class, 'getLabourStatusListReceived']);
        Route::post('/update-officer-labour-status-approved', [OfficerController::class, 'updateLabourStatusApproved']);
        Route::post('/update-officer-labour-status-not-approved', [OfficerController::class, 'updateLabourStatusNotApproved']);
        Route::post('/update-officer-labour-status-rejected', [OfficerController::class, 'updateLabourStatusRejected']);
        Route::post('/officer-reports-count', [OfficerController::class, 'officerReportsCount']);
        Route::post('/list-particular-officer-labour-details', [OfficerController::class, 'getParticularLabourOfficer']);
        Route::post('/list-attendance-marked-visible-for-officer', [AttendanceMarkVisibleForOfficerController::class, 'getAllAttendanceMarkedLabour']);
        // Route::post('/project-list-for-officer', [AttendanceMarkVisibleForOfficerController::class, 'getAllProjectListForOfficer']);
       
        Route::post('logout', [AuthController::class, 'logout']);
    });
});






