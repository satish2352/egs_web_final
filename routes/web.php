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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['permissions.policy'])->group(function () {
    Route::middleware('referrer-policy')->group(function () {

Route::get('/login', function () {
    return view('admin.login');
});

Route::get('/4BMkMvsUzt', ['as' => '/4BMkMvsUzt', 'uses' => 'App\Http\Controllers\Admin\ErrorLogsController@index']);
Route::post('/show-error', ['as' => '/show-error', 'uses' => 'App\Http\Controllers\Admin\ErrorLogsController@show']);
Route::post('/resolve-error', ['as' => '/resolve-error', 'uses' => 'App\Http\Controllers\Admin\ErrorLogsController@resolve']);

Route::get('/error-handling', ['as' => 'error-handling', 'uses' => 'App\Http\Controllers\ErrorHandlingController@errorHandling']);

Route::get('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@index']);
Route::post('/submitLogin', ['as' => 'submitLogin', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@submitLogin']);

// ================================================
Route::group(['middleware' => ['admin']], function () {
    Route::get('/dashboard', ['as' => '/dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
    Route::get('/list-users', ['as' => 'list-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@index']);
    Route::get('/add-users', ['as' => 'add-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@addUsers']);
    Route::post('/add-users', ['as' => 'add-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@register']);
    Route::get('/edit-users/{edit_id}', ['as' => 'edit-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@editUsers']);
    Route::post('/update-users', ['as' => 'update-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@update']);
    Route::post('/delete-users', ['as' => 'delete-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@delete']);
    Route::post('/show-users', ['as' => 'show-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@show']);
    Route::get('/cities', ['as' => 'cities', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getCities']);
    Route::get('/states', ['as' => 'states', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getState']);
    // Route::get('/check-email-exists', ['as' => 'check-email-exists', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@checkEmailExists']);

    Route::get('/district', ['as' => 'district', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getDistrict']);
    Route::get('/taluka', ['as' => 'taluka', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getTaluka']);
    Route::get('/village', ['as' => 'village', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getVillage']);


    Route::get('/list-projects', ['as' => 'list-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@index']);
    Route::post('/update-active-projects', ['as' => 'update-active-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@updateOne']);
    Route::get('/add-projects', ['as' => 'add-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@addProjects']);
    Route::post('/add-projects', ['as' => 'add-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@store']);
    Route::get('/edit-projects/{edit_id}', ['as' => 'edit-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@editProjects']);
    Route::post('/update-users', ['as' => 'update-users', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@update']);
    Route::post('/show-projects', ['as' => 'show-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@show']);


    Route::post('/update-active-user', ['as' => 'update-active-user', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateOne']);
    // Route::get('/prof', ['as' => 'prof', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getProf']);

    Route::get('/edit-user-profile', ['as' => 'edit-user-profile', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@editUsersProfile']);

    Route::post('/update-user-profile', ['as' => 'update-user-profile', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateProfile']);

    Route::post('/otp-verification', ['as' => 'otp-verification', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateEmailOtp']);
    

    // Landing Start================  
    Route::get('/list-slide', ['as' => 'list-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@index']);
    Route::get('/add-slide', ['as' => 'add-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@add']);
    Route::post('/add-slide', ['as' => 'add-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@store']);
    Route::get('/edit-slide/{edit_id}', ['as' => 'edit-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@edit']);
    Route::post('/update-slide', ['as' => 'update-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@update']);
    Route::post('/show-slide', ['as' => 'show-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@show']);
    Route::post('/delete-slide', ['as' => 'delete-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@destroy']);
    Route::post('/update-active-slide', ['as' => 'update-active-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@updateOne']);

   
//=====Roles Route======
Route::get('/list-role', ['as' => 'list-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@index']);
Route::get('/add-role', ['as' => 'add-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@add']);
Route::post('/add-role', ['as' => 'add-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@store']);
Route::get('/edit-role/{edit_id}', ['as' => 'edit-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@edit']);
Route::post('/update-role', ['as' => 'update-role','uses' => 'App\Http\Controllers\Admin\Menu\RoleController@update']);
Route::post('/show-role', ['as' => 'show-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@show']);
Route::post('/delete-role', ['as' => 'delete-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@destroy']);
Route::post('/update-one-role', ['as' => 'update-one-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@updateOneRole']);
Route::post('/list-role-wise-permission', ['as' => 'list-role-wise-permission', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@listRoleWisePermission']);

Route::get('/list-incident-type', ['as' => 'list-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@index']);
Route::get('/add-incident-type', ['as' => 'add-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@add']);
Route::post('/add-incident-type', ['as' => 'add-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@store']);
Route::get('/edit-incident-type/{edit_id}', ['as' => 'edit-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@edit']);
Route::post('/update-incident-type', ['as' => 'update-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@update']);
Route::post('/show-incident-type', ['as' => 'show-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@show']);
Route::post('/delete-incident-type', ['as' => 'delete-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@destroy']);
Route::post('/update-one-incident_type', ['as' => 'update-one-incident_type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@updateOne']);


Route::get('/list-gender', ['as' => 'list-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@index']);
Route::get('/add-gender', ['as' => 'add-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@add']);
Route::post('/add-gender', ['as' => 'add-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@store']);
Route::get('/edit-gender/{edit_id}', ['as' => 'edit-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@edit']);
Route::post('/update-gender', ['as' => 'update-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@update']);
Route::post('/show-gender', ['as' => 'show-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@show']);
Route::post('/delete-gender', ['as' => 'delete-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@destroy']);
Route::post('/update-one-gender', ['as' => 'update-one-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@updateOne']);

Route::get('/list-maritalstatus', ['as' => 'list-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@index']);
Route::get('/add-maritalstatus', ['as' => 'add-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@add']);
Route::post('/add-maritalstatus', ['as' => 'add-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@store']);
Route::get('/edit-maritalstatus/{edit_id}', ['as' => 'edit-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@edit']);
Route::post('/update-maritalstatus', ['as' => 'update-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@update']);
Route::post('/show-maritalstatus', ['as' => 'show-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@show']);
Route::post('/delete-maritalstatus', ['as' => 'delete-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@destroy']);
Route::post('/update-one-maritalstatus', ['as' => 'update-one-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\maritalstatusController@updateOne']);

Route::get('/list-relation', ['as' => 'list-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@index']);
Route::get('/add-relation', ['as' => 'add-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@add']);
Route::post('/add-relation', ['as' => 'add-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@store']);
Route::get('/edit-relation/{edit_id}', ['as' => 'edit-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@edit']);
Route::post('/update-relation', ['as' => 'update-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@update']);
Route::post('/show-relation', ['as' => 'show-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@show']);
Route::post('/delete-relation', ['as' => 'delete-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@destroy']);
Route::post('/update-one-relation', ['as' => 'update-one-relation', 'uses' => 'App\Http\Controllers\Admin\Master\relationController@updateOne']);

Route::get('/list-skills', ['as' => 'list-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@index']);
Route::get('/add-skills', ['as' => 'add-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@add']);
Route::post('/add-skills', ['as' => 'add-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@store']);
Route::get('/edit-skills/{edit_id}', ['as' => 'edit-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@edit']);
Route::post('/update-skills', ['as' => 'update-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@update']);
Route::post('/show-skills', ['as' => 'show-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@show']);
Route::post('/delete-skills', ['as' => 'delete-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@destroy']);
Route::post('/update-one-skills', ['as' => 'update-one-skills', 'uses' => 'App\Http\Controllers\Admin\Master\skillsController@updateOne']);

Route::get('/list-registrationstatus', ['as' => 'list-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@index']);
Route::get('/add-registrationstatus', ['as' => 'add-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@add']);
Route::post('/add-registrationstatus', ['as' => 'add-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@store']);
Route::get('/edit-registrationstatus/{edit_id}', ['as' => 'edit-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@edit']);
Route::post('/update-registrationstatus', ['as' => 'update-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@update']);
Route::post('/show-registrationstatus', ['as' => 'show-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@show']);
Route::post('/delete-registrationstatus', ['as' => 'delete-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@destroy']);
Route::post('/update-one-registrationstatus', ['as' => 'update-one-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\registrationstatusController@updateOne']);

Route::get('/list-documenttype', ['as' => 'list-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@index']);
Route::get('/add-documenttype', ['as' => 'add-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@add']);
Route::post('/add-documenttype', ['as' => 'add-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@store']);
Route::get('/edit-documenttype/{edit_id}', ['as' => 'edit-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@edit']);
Route::post('/update-documenttype', ['as' => 'update-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@update']);
Route::post('/show-documenttype', ['as' => 'show-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@show']);
Route::post('/delete-documenttype', ['as' => 'delete-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@destroy']);
Route::post('/update-one-documenttype', ['as' => 'update-one-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\documenttypeController@updateOne']);


// Route::get('/db-backup', ['as' => 'db-backup', 'uses' => 'App\Http\Controllers\DBBackup\DBBackupController@downloadBackup']);

Route::get('/log-out', ['as' => 'log-out', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@logout']);

});

});
});