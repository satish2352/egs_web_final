<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', function () {
    return view('admin.login');
});
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

    Route::post('/update-active-user', ['as' => 'update-active-user', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateOne']);
    // Route::get('/prof', ['as' => 'prof', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getProf']);

    Route::get('/edit-user-profile', ['as' => 'edit-user-profile', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@editUsersProfile']);

    Route::post('/update-user-profile', ['as' => 'update-user-profile', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateProfile']);

    Route::post('/otp-verification', ['as' => 'otp-verification', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateEmailOtp']);
    

    // Landing Start================

    Route::get('/list-landing-slide', ['as' => 'list-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@index']);
    Route::get('/add-landing-slide', ['as' => 'add-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@add']);
    Route::post('/add-landing-slide', ['as' => 'add-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@store']);
    Route::get('/edit-landing-slide/{edit_id}', ['as' => 'edit-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@edit']);
    Route::post('/update-landing-slide', ['as' => 'update-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@update']);
    Route::post('/show-landing-slide', ['as' => 'show-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@show']);
    Route::post('/delete-landing-slide', ['as' => 'delete-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@destroy']);
    Route::post('/update-active-landing-slide', ['as' => 'update-active-landing-slide', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingSliderController@updateOne']);
    

    Route::get('/list-landing-content', ['as' => 'list-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@index']);
    Route::get('/add-landing-content', ['as' => 'add-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@add']);
    Route::post('/add-landing-content', ['as' => 'add-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@store']);
    Route::get('/edit-landing-content/{edit_id}', ['as' => 'edit-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@edit']);
    Route::post('/update-landing-content', ['as' => 'update-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@update']);
    Route::post('/show-landing-content', ['as' => 'show-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@show']);
    Route::post('/delete-landing-content', ['as' => 'delete-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@destroy']);
    Route::post('/update-active-landing-content', ['as' => 'update-active-landing-content', 'uses' => 'App\Http\Controllers\Admin\Landing\LandingContentController@updateOne']);
    
    // Landing End================
    Route::get('/list-main-menu', ['as' => 'list-main-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\MainMenuController@index']);
    Route::get('/add-main-menu', ['as' => 'add-main-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\MainMenuController@add']);
    Route::post('/add-main-menu', ['as' => 'add-main-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\MainMenuController@store']);
    Route::post('/show-main-menu', ['as' => 'show-main-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\MainMenuController@show']);
    Route::post('/delete-main-menu', ['as' => 'delete-main-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\MainMenuController@destroy']);
    Route::get('/edit-main-menu/{edit_id}', ['as' => 'edit-main-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\MainMenuController@edit']);
    Route::post('/update-main-menu', ['as' => 'update-main-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\MainMenuController@update']);

    Route::get('/list-sub-menu', ['as' => 'list-sub-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\SubMenuController@index']);
    Route::get('/add-sub-menu', ['as' => 'add-sub-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\SubMenuController@add']);
    Route::post('/add-sub-menu', ['as' => 'add-sub-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\SubMenuController@store']);
    Route::post('/show-sub-menu', ['as' => 'show-sub-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\SubMenuController@show']);
    Route::post('/delete-sub-menu', ['as' => 'delete-sub-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\SubMenuController@destroy']);
    Route::get('/edit-sub-menu/{edit_id}', ['as' => 'edit-sub-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\SubMenuController@edit']);
    Route::post('/update-sub-menu', ['as' => 'update-sub-menu', 'uses' => 'App\Http\Controllers\Admin\Menu\SubMenuController@update']);


    Route::get('/list-dynamic-page', ['as' => 'list-dynamic-page', 'uses' => 'App\Http\Controllers\Admin\DynamicPages\DynamicPagesController@index']);
    Route::get('/add-dynamic-page', ['as' => 'add-dynamic-page', 'uses' => 'App\Http\Controllers\Admin\DynamicPages\DynamicPagesController@add']);
    Route::post('/add-dynamic-page', ['as' => 'add-dynamic-page', 'uses' => 'App\Http\Controllers\Admin\DynamicPages\DynamicPagesController@store']);
    Route::post('/show-dynamic-page', ['as' => 'show-dynamic-page', 'uses' => 'App\Http\Controllers\Admin\DynamicPages\DynamicPagesController@show']);
    Route::post('/delete-dynamic-page', ['as' => 'delete-dynamic-page', 'uses' => 'App\Http\Controllers\Admin\DynamicPages\DynamicPagesController@destroy']);
    Route::get('/edit-dynamic-page/{edit_id}', ['as' => 'edit-dynamic-page', 'uses' => 'App\Http\Controllers\Admin\DynamicPages\DynamicPagesController@edit']);
    Route::post('/update-dynamic-page', ['as' => 'update-dynamic-page', 'uses' => 'App\Http\Controllers\Admin\DynamicPages\DynamicPagesController@update']);

    Route::get('/list-marquee', ['as' => 'list-marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@index']);
    Route::get('/add-marquee', ['as' => 'add-marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@add']);
    Route::post('/add-marquee', ['as' => 'add-marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@store']);
    Route::get('/edit-marquee/{edit_id}', ['as' => 'edit-marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@edit']);
    Route::post('/update-marquee', ['as' => 'update-marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@update']);
    Route::post('/show-marquee', ['as' => 'show-marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@show']);
    Route::post('/delete-marquee', ['as' => 'delete-marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@destroy']);
    Route::post('/update-one_marquee', ['as' => 'update-one_marquee', 'uses' => 'App\Http\Controllers\Admin\Home\MarqueeController@updateOne']);

    Route::get('/list-slide', ['as' => 'list-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@index']);
    Route::get('/add-slide', ['as' => 'add-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@add']);
    Route::post('/add-slide', ['as' => 'add-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@store']);
    Route::get('/edit-slide/{edit_id}', ['as' => 'edit-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@edit']);
    Route::post('/update-slide', ['as' => 'update-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@update']);
    Route::post('/show-slide', ['as' => 'show-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@show']);
    Route::post('/delete-slide', ['as' => 'delete-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@destroy']);
    Route::post('/update-active-slide', ['as' => 'update-active-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@updateOne']);
    
    Route::get('/list-weather', ['as' => 'list-weather', 'uses' => 'App\Http\Controllers\Admin\Home\WeatherController@index']);
    Route::get('/add-weather', ['as' => 'add-weather', 'uses' => 'App\Http\Controllers\Admin\Home\WeatherController@add']);
    Route::post('/add-weather', ['as' => 'add-weather', 'uses' => 'App\Http\Controllers\Admin\Home\WeatherController@store']);
    Route::get('/edit-weather/{edit_id}', ['as' => 'edit-weather', 'uses' => 'App\Http\Controllers\Admin\Home\WeatherController@edit']);
    Route::post('/update-weather', ['as' => 'update-weather', 'uses' => 'App\Http\Controllers\Admin\Home\WeatherController@update']);
    Route::post('/show-weather', ['as' => 'show-weather', 'uses' => 'App\Http\Controllers\Admin\Home\WeatherController@show']);
    Route::post('/delete-weather', ['as' => 'delete-weather', 'uses' => 'App\Http\Controllers\Admin\Home\WeatherController@destroy']);

    Route::get('/list-disasterforcast', ['as' => 'list-disasterforcast', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterForcastController@index']);
    Route::get('/add-disasterforcast', ['as' => 'add-disasterforcast', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterForcastController@add']);
    Route::post('/add-disasterforcast', ['as' => 'add-disasterforcast', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterForcastController@store']);
    Route::get('/edit-disasterforcast/{edit_id}', ['as' => 'edit-disasterforcast', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterForcastController@edit']);
    Route::post('/update-disasterforcast', ['as' => 'update-disasterforcast', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterForcastController@update']);
    Route::post('/show-disasterforcast', ['as' => 'show-disasterforcast', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterForcastController@show']);
    Route::post('/delete-disasterforcast', ['as' => 'delete-disasterforcast', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterForcastController@destroy']);

    Route::get('/list-department-information', ['as' => 'list-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@index']);
    Route::get('/add-department-information', ['as' => 'add-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@add']);
    Route::post('/add-department-information', ['as' => 'add-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@store']);
    Route::get('/edit-department-information/{edit_id}', ['as' => 'edit-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@edit']);
    Route::post('/update-department-information', ['as' => 'update-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@update']);
    Route::post('/show-department-information', ['as' => 'show-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@show']);
    Route::post('/delete-department-information', ['as' => 'delete-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@destroy']);
    Route::post('/update-one-department-information', ['as' => 'update-one-department-information', 'uses' => 'App\Http\Controllers\Admin\Home\DepartmentInformationController@updateOne']);

    Route::get('/list-emergency-contact', ['as' => 'list-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@index']);
    Route::get('/add-emergency-contact', ['as' => 'add-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@add']);
    Route::post('/add-emergency-contact', ['as' => 'add-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@store']);
    Route::get('/edit-emergency-contact/{edit_id}', ['as' => 'edit-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@edit']);
    Route::post('/update-emergency-contact', ['as' => 'update-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@update']);
    Route::post('/show-emergency-contact', ['as' => 'show-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@show']);
    Route::post('/delete-emergency-contact', ['as' => 'delete-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@destroy']);
    Route::post('/update-one-emergency-contact', ['as' => 'update-one-emergency-contact', 'uses' => 'App\Http\Controllers\Admin\Home\EmergencyContactController@updateOne']);


    Route::get('/list-disaster-management-news', ['as' => 'list-disaster-management-news', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@index']);
    Route::get('/add-disaster-management-news', ['as' => 'add-disaster-management-news', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@add']);
    Route::post('/add-disaster-management-news', ['as' => 'add-disaster-management-news', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@store']);
    Route::get('/edit-disaster-management-news/{edit_id}', ['as' => 'edit-disaster-management-news', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@edit']);
    Route::post('/update-disaster-management-news', ['as' => 'update-disaster-management-news', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@update']);
    Route::post('/show-disaster-management-news', ['as' => 'show-disaster-management-news', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@show']);
    Route::post('/delete-disaster-management-news', ['as' => 'delete-disaster-management-news', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@destroy']);
    Route::post('/update-one', ['as' => 'update-one', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementNewsController@updateOne']);

    Route::get('/list-website-contact', ['as' => 'list-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@index']);
    Route::get('/add-website-contact', ['as' => 'add-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@add']);
    Route::post('/add-website-contact', ['as' => 'add-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@store']);
    Route::get('/edit-website-contact/{edit_id}', ['as' => 'edit-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@edit']);
    Route::post('/update-website-contact', ['as' => 'update-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@update']);
    Route::post('/show-website-contact', ['as' => 'show-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@show']);
    Route::post('/delete-website-contact', ['as' => 'delete-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@destroy']);
    Route::post('/update-one-website-contact', ['as' => 'update-one-website-contact', 'uses' => 'App\Http\Controllers\Admin\Home\WebsiteContactController@updateOne']);
    
    Route::get('/list-disaster-management-web-portal', ['as' => 'list-disaster-management-web-portal', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementWebPortalController@index']);
    Route::get('/add-disaster-management-web-portal', ['as' => 'add-disaster-management-web-portal', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementWebPortalController@add']);
    Route::post('/add-disaster-management-web-portal', ['as' => 'add-disaster-management-web-portal', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementWebPortalController@store']);
    Route::get('/edit-disaster-management-web-portal/{edit_id}', ['as' => 'edit-disaster-management-web-portal', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementWebPortalController@edit']);
    Route::post('/update-disaster-management-web-portal', ['as' => 'update-disaster-management-web-portal', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementWebPortalController@update']);
    Route::post('/show-disaster-management-web-portal', ['as' => 'show-disaster-management-web-portal', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementWebPortalController@show']);
    Route::post('/delete-disaster-management-web-portal', ['as' => 'delete-disaster-management-web-portal', 'uses' => 'App\Http\Controllers\Admin\Home\DisasterManagementWebPortalController@destroy']);
    
    Route::get('/list-objectivegoals', ['as' => 'list-objectivegoals', 'uses' => 'App\Http\Controllers\Admin\Aboutus\ObjectiveGoalsController@index']);
    Route::get('/add-objectivegoals', ['as' => 'add-objectivegoals', 'uses' => 'App\Http\Controllers\Admin\Aboutus\ObjectiveGoalsController@add']);
    Route::post('/add-objectivegoals', ['as' => 'add-objectivegoals', 'uses' => 'App\Http\Controllers\Admin\Aboutus\ObjectiveGoalsController@store']);
    Route::post('/show-objectivegoals', ['as' => 'show-objectivegoals', 'uses' => 'App\Http\Controllers\Admin\Aboutus\ObjectiveGoalsController@show']);
    Route::post('/delete-objectivegoals', ['as' => 'delete-objectivegoals', 'uses' => 'App\Http\Controllers\Admin\Aboutus\ObjectiveGoalsController@destroy']);
    Route::get('/edit-objectivegoals/{edit_id}', ['as' => 'edit-objectivegoals', 'uses' => 'App\Http\Controllers\Admin\Aboutus\ObjectiveGoalsController@edit']);
    Route::post('/update-objectivegoals', ['as' => 'update-objectivegoals', 'uses' => 'App\Http\Controllers\Admin\Aboutus\ObjectiveGoalsController@update']);
    
    Route::get('/list-disastermanagementportal', ['as' => 'list-disastermanagementportal', 'uses' => 'App\Http\Controllers\Admin\Aboutus\DisasterManagementPortalController@index']);
    Route::get('/add-disastermanagementportal', ['as' => 'add-disastermanagementportal', 'uses' => 'App\Http\Controllers\Admin\Aboutus\DisasterManagementPortalController@add']);
    Route::post('/add-disastermanagementportal', ['as' => 'add-disastermanagementportal', 'uses' => 'App\Http\Controllers\Admin\Aboutus\DisasterManagementPortalController@store']);
    Route::post('/show-disastermanagementportal', ['as' => 'show-disastermanagementportal', 'uses' => 'App\Http\Controllers\Admin\Aboutus\DisasterManagementPortalController@show']);
    Route::post('/delete-disastermanagementportal', ['as' => 'delete-disastermanagementportal', 'uses' => 'App\Http\Controllers\Admin\Aboutus\DisasterManagementPortalController@destroy']);
    Route::get('/edit-disastermanagementportal/{edit_id}', ['as' => 'edit-disastermanagementportal', 'uses' => 'App\Http\Controllers\Admin\Aboutus\DisasterManagementPortalController@edit']);
    Route::post('/update-disastermanagementportal', ['as' => 'update-disastermanagementportal', 'uses' => 'App\Http\Controllers\Admin\Aboutus\DisasterManagementPortalController@update']);
    
    Route::get('/list-statedisastermanagementauthority', ['as' => 'list-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@index']);
    Route::get('/add-statedisastermanagementauthority', ['as' => 'add-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@add']);
    Route::post('/add-statedisastermanagementauthority', ['as' => 'add-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@store']);
    Route::post('/show-statedisastermanagementauthority', ['as' => 'show-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@show']);
    Route::post('/delete-statedisastermanagementauthority', ['as' => 'delete-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@destroy']);
    Route::get('/edit-statedisastermanagementauthority/{edit_id}', ['as' => 'edit-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@edit']);
    Route::post('/update-statedisastermanagementauthority', ['as' => 'update-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@update']);
    
    // Route::get('/edit-statedisastermanagementauthority/', ['as' => 'edit-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@edit']);
    // Route::post('/update-statedisastermanagementauthority', ['as' => 'update-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@update']);

    // Edit form route
    // Route::get('/edit-statedisastermanagementauthority/{id}', ['as' => 'edit-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@edit']);
    // // Update form route
    // Route::put('/update-statedisastermanagementauthority/{id}', ['as' => 'update-statedisastermanagementauthority', 'uses' => 'App\Http\Controllers\Admin\Aboutus\StateDisasterManagementAuthorityController@update']);




    Route::get('/list-metadata', ['as' => 'list-metadata', 'uses' => 'App\Http\Controllers\MetadataController@index']);
    Route::get('/add-metadata', ['as' => 'add-metadata', 'uses' => 'App\Http\Controllers\MetadataController@add']);
    Route::post('/add-metadata', ['as' => 'add-metadata', 'uses' => 'App\Http\Controllers\MetadataController@store']);
    Route::post('/show-metadata', ['as' => 'show-metadata', 'uses' => 'App\Http\Controllers\MetadataController@show']);
    Route::post('/delete-metadata', ['as' => 'delete-metadata', 'uses' => 'App\Http\Controllers\MetadataController@destroy']);
    Route::get('/edit-metadata/{edit_id}', ['as' => 'edit-metadata', 'uses' => 'App\Http\Controllers\MetadataController@edit']);
    Route::post('/update-metadata', ['as' => 'update-metadata', 'uses' => 'App\Http\Controllers\MetadataController@update']);    

    // ==================Preparedness============
    Route::get('/list-hazard-vulnerability-assessment', ['as' => 'list-hazard-vulnerability-assessment', 'uses' => 'App\Http\Controllers\Admin\Preparedness\HazardVulnerabilityController@index']);
    Route::get('/add-hazard-vulnerability-assessment', ['as' => 'add-hazard-vulnerability-assessment', 'uses' => 'App\Http\Controllers\Admin\Preparedness\HazardVulnerabilityController@add']);
    Route::post('/add-hazard-vulnerability-assessment', ['as' => 'add-hazard-vulnerability-assessment', 'uses' => 'App\Http\Controllers\Admin\Preparedness\HazardVulnerabilityController@store']);
    Route::get('/edit-hazard-vulnerability-assessment/{edit_id}', ['as' => 'edit-hazard-vulnerability-assessment', 'uses' => 'App\Http\Controllers\Admin\Preparedness\HazardVulnerabilityController@edit']);
    Route::post('/update-hazard-vulnerability-assessment', ['as' => 'update-hazard-vulnerability-assessment', 'uses' => 'App\Http\Controllers\Admin\Preparedness\HazardVulnerabilityController@update']);
    Route::post('/show-hazard-vulnerability-assessment', ['as' => 'show-hazard-vulnerability-assessment', 'uses' => 'App\Http\Controllers\Admin\Preparedness\HazardVulnerabilityController@show']);
    Route::post('/delete-hazard-vulnerability-assessment', ['as' => 'delete-hazard-vulnerability-assessment', 'uses' => 'App\Http\Controllers\Admin\Preparedness\HazardVulnerabilityController@destroy']);

    Route::get('/list-early-warning-system', ['as' => 'list-early-warning-system', 'uses' => 'App\Http\Controllers\Admin\Preparedness\EarlyWarningSystemController@index']);
    Route::get('/add-early-warning-system', ['as' => 'add-early-warning-system', 'uses' => 'App\Http\Controllers\Admin\Preparedness\EarlyWarningSystemController@add']);
    Route::post('/add-early-warning-system', ['as' => 'add-early-warning-system', 'uses' => 'App\Http\Controllers\Admin\Preparedness\EarlyWarningSystemController@store']);
    Route::get('/edit-early-warning-system/{edit_id}', ['as' => 'edit-early-warning-system', 'uses' => 'App\Http\Controllers\Admin\Preparedness\EarlyWarningSystemController@edit']);
    Route::post('/update-early-warning-system', ['as' => 'update-early-warning-system', 'uses' => 'App\Http\Controllers\Admin\Preparedness\EarlyWarningSystemController@update']);
    Route::post('/show-early-warning-system', ['as' => 'show-early-warning-system', 'uses' => 'App\Http\Controllers\Admin\Preparedness\EarlyWarningSystemController@show']);
    Route::post('/delete-early-warning-system', ['as' => 'delete-early-warning-system', 'uses' => 'App\Http\Controllers\Admin\Preparedness\EarlyWarningSystemController@destroy']);
    
    Route::get('/list-public-awareness-and-education', ['as' => 'list-public-awareness-and-education', 'uses' => 'App\Http\Controllers\Admin\Preparedness\PublicAwarenessEducationController@index']);
    Route::get('/add-public-awareness-and-education', ['as' => 'add-public-awareness-and-education', 'uses' => 'App\Http\Controllers\Admin\Preparedness\PublicAwarenessEducationController@add']);
    Route::post('/add-public-awareness-and-education', ['as' => 'add-public-awareness-and-education', 'uses' => 'App\Http\Controllers\Admin\Preparedness\PublicAwarenessEducationController@store']);
    Route::get('/edit-public-awareness-and-education/{edit_id}', ['as' => 'edit-public-awareness-and-education', 'uses' => 'App\Http\Controllers\Admin\Preparedness\PublicAwarenessEducationController@edit']);
    Route::post('/update-public-awareness-and-education', ['as' => 'update-public-awareness-and-education', 'uses' => 'App\Http\Controllers\Admin\Preparedness\PublicAwarenessEducationController@update']);
    Route::post('/show-public-awareness-and-education', ['as' => 'show-public-awareness-and-education', 'uses' => 'App\Http\Controllers\Admin\Preparedness\PublicAwarenessEducationController@show']);
    Route::post('/delete-public-awareness-and-education', ['as' => 'delete-public-awareness-and-education', 'uses' => 'App\Http\Controllers\Admin\Preparedness\PublicAwarenessEducationController@destroy']);

    Route::get('/list-govt-hospitals', ['as' => 'list-govt-hospitals', 'uses' => 'App\Http\Controllers\Admin\Preparedness\GovtHospitalsController@index']);
    Route::get('/add-govt-hospitals', ['as' => 'add-govt-hospitals', 'uses' => 'App\Http\Controllers\Admin\Preparedness\GovtHospitalsController@add']);
    Route::post('/add-govt-hospitals', ['as' => 'add-govt-hospitals', 'uses' => 'App\Http\Controllers\Admin\Preparedness\GovtHospitalsController@store']);
    Route::get('/edit-govt-hospitals/{edit_id}', ['as' => 'edit-govt-hospitals', 'uses' => 'App\Http\Controllers\Admin\Preparedness\GovtHospitalsController@edit']);
    Route::post('/update-govt-hospitals', ['as' => 'update-govt-hospitals', 'uses' => 'App\Http\Controllers\Admin\Preparedness\GovtHospitalsController@update']);
    Route::post('/show-govt-hospitals', ['as' => 'show-govt-hospitals', 'uses' => 'App\Http\Controllers\Admin\Preparedness\GovtHospitalsController@show']);
    Route::post('/delete-govt-hospitals', ['as' => 'delete-govt-hospitals', 'uses' => 'App\Http\Controllers\Admin\Preparedness\GovtHospitalsController@destroy']);
    // ==========EmergencyResponse=======
    Route::get('/list-state-emergency-operations-center', ['as' => 'list-state-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\StateEmergencyOperationsCenterController@index']);
    Route::get('/add-state-emergency-operations-center', ['as' => 'add-state-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\StateEmergencyOperationsCenterController@add']);
    Route::post('/add-state-emergency-operations-center', ['as' => 'add-state-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\StateEmergencyOperationsCenterController@store']);
    Route::get('/edit-state-emergency-operations-center/{edit_id}', ['as' => 'edit-state-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\StateEmergencyOperationsCenterController@edit']);
    Route::post('/update-state-emergency-operations-center', ['as' => 'update-state-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\StateEmergencyOperationsCenterController@update']);
    Route::post('/show-state-emergency-operations-center', ['as' => 'show-state-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\StateEmergencyOperationsCenterController@show']);
    Route::post('/delete-state-emergency-operations-center', ['as' => 'delete-state-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\StateEmergencyOperationsCenterController@destroy']);

    Route::get('/list-district-emergency-operations-center', ['as' => 'list-district-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\DistrictEmergencyOperationsCenterController@index']);
    Route::get('/add-district-emergency-operations-center', ['as' => 'add-district-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\DistrictEmergencyOperationsCenterController@add']);
    Route::post('/add-district-emergency-operations-center', ['as' => 'add-district-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\DistrictEmergencyOperationsCenterController@store']);
    Route::get('/edit-district-emergency-operations-center/{edit_id}', ['as' => 'edit-district-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\DistrictEmergencyOperationsCenterController@edit']);
    Route::post('/update-district-emergency-operations-center', ['as' => 'update-district-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\DistrictEmergencyOperationsCenterController@update']);
    Route::post('/show-district-emergency-operations-center', ['as' => 'show-district-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\DistrictEmergencyOperationsCenterController@show']);
    Route::post('/delete-district-emergency-operations-center', ['as' => 'delete-district-emergency-operations-center', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\DistrictEmergencyOperationsCenterController@destroy']);

    Route::get('/list-emergency-contact-numbers', ['as' => 'list-emergency-contact-numbers', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@index']);
    Route::get('/add-emergency-contact-numbers', ['as' => 'add-emergency-contact-numbers', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@add']);
    Route::post('/add-emergency-contact-numbers', ['as' => 'add-emergency-contact-numbers', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@store']);
    Route::get('/edit-emergency-contact-numbers', ['as' => 'edit-emergency-contact-numbers', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@edit']);
    Route::post('/update-emergency-contact-numbers', ['as' => 'update-emergency-contact-numbers', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@update']);
    Route::post('/delete-emergency-contact-numbers', ['as' => 'delete-emergency-contact-numbers', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@destroy']);
   
    Route::get('/add-more-emergency-contact-data', ['as' => 'add-more-emergency-contact-data', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@addmore']);
    Route::post('/add-more-emergency-contact-data', ['as' => 'add-more-emergency-contact-data', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@storeaddmore']);
    Route::post('/add-more-emergency-contact-data-delete', ['as' => 'add-more-emergency-contact-data-delete', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EmergencyContactNumbersController@deleteaddmore']);
    
    Route::get('/list-evacuation-plans', ['as' => 'list-evacuation-plans', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EvacuationPlansController@index']);
    Route::get('/add-evacuation-plans', ['as' => 'add-evacuation-plans', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EvacuationPlansController@add']);
    Route::post('/add-evacuation-plans', ['as' => 'add-evacuation-plans', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EvacuationPlansController@store']);
    Route::get('/edit-evacuation-plans/{edit_id}', ['as' => 'edit-evacuation-plans', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EvacuationPlansController@edit']);
    Route::post('/update-evacuation-plans', ['as' => 'update-evacuation-plans', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EvacuationPlansController@update']);
    Route::post('/show-evacuation-plans', ['as' => 'show-evacuation-plans', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EvacuationPlansController@show']);
    Route::post('/delete-evacuation-plans', ['as' => 'delete-evacuation-plans', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\EvacuationPlansController@destroy']);
    
    Route::get('/list-relief-measures-resources', ['as' => 'list-relief-measures-resources', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\ReliefMeasuresResourcesController@index']);
    Route::get('/add-relief-measures-resources', ['as' => 'add-relief-measures-resources', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\ReliefMeasuresResourcesController@add']);
    Route::post('/add-relief-measures-resources', ['as' => 'add-relief-measures-resources', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\ReliefMeasuresResourcesController@store']);
    Route::get('/edit-relief-measures-resources/{edit_id}', ['as' => 'edit-relief-measures-resources', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\ReliefMeasuresResourcesController@edit']);
    Route::post('/update-relief-measures-resources', ['as' => 'update-relief-measures-resources', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\ReliefMeasuresResourcesController@update']);
    Route::post('/show-relief-measures-resources', ['as' => 'show-relief-measures-resources', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\ReliefMeasuresResourcesController@show']);
    Route::post('/delete-relief-measures-resources', ['as' => 'delete-relief-measures-resources', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\ReliefMeasuresResourcesController@destroy']);

    Route::get('/list-search-rescue-teams', ['as' => 'list-search-rescue-teams', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\SearchRescueTeamsController@index']);
    Route::get('/add-search-rescue-teams', ['as' => 'add-search-rescue-teams', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\SearchRescueTeamsController@add']);
    Route::post('/add-search-rescue-teams', ['as' => 'add-search-rescue-teams', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\SearchRescueTeamsController@store']);
    Route::get('/edit-search-rescue-teams/{edit_id}', ['as' => 'edit-search-rescue-teams', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\SearchRescueTeamsController@edit']);
    Route::post('/update-search-rescue-teams', ['as' => 'update-search-rescue-teams', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\SearchRescueTeamsController@update']);
    Route::post('/show-search-rescue-teams', ['as' => 'show-search-rescue-teams', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\SearchRescueTeamsController@show']);
    Route::post('/delete-search-rescue-teams', ['as' => 'delete-search-rescue-teams', 'uses' => 'App\Http\Controllers\Admin\EmergencyResponse\SearchRescueTeamsController@destroy']);

// ===== Citizen Action=======
Route::get('/list-incident-type', ['as' => 'list-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@index']);
Route::get('/add-incident-type', ['as' => 'add-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@add']);
Route::post('/add-incident-type', ['as' => 'add-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@store']);
Route::get('/edit-incident-type/{edit_id}', ['as' => 'edit-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@edit']);
Route::post('/update-incident-type', ['as' => 'update-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@update']);
Route::post('/show-incident-type', ['as' => 'show-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@show']);
Route::post('/delete-incident-type', ['as' => 'delete-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@destroy']);
Route::post('/update-one-incident_type', ['as' => 'update-one-incident_type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@updateOne']);

Route::get('/list-incident-modal-info', ['as' => 'list-incident-modal-info', 'uses' => 'App\Http\Controllers\Admin\CitizenAction\ReportIncidentModalController@index']);
Route::get('/list-volunteer-modal-info', ['as' => 'list-volunteer-modal-info', 'uses' => 'App\Http\Controllers\Admin\CitizenAction\VolunteerCitizenModalController@index']);
Route::get('/list-feedback-modal-info', ['as' => 'list-feedback-modal-info', 'uses' => 'App\Http\Controllers\Admin\CitizenAction\FeedbackCitizenModalController@index']);
Route::post('/delete-incident-modal-info', ['as' => 'delete-incident-modal-info', 'uses' => 'App\Http\Controllers\Admin\CitizenAction\ReportIncidentModalController@destroy']);
Route::post('/delete-volunteer-modal-info', ['as' => 'delete-volunteer-modal-info', 'uses' => 'App\Http\Controllers\Admin\CitizenAction\VolunteerCitizenModalController@destroy']);
Route::post('/show-volunteer-modal-info', ['as' => 'show-volunteer-modal-info', 'uses' => 'App\Http\Controllers\Admin\CitizenAction\VolunteerCitizenModalController@show']);

//=======Header=======
Route::get('/list-social-icon', ['as' => 'list-social-icon', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@index']);
Route::get('/add-social-icon', ['as' => 'add-social-icon', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@add']);
Route::post('/add-social-icon', ['as' => 'add-social-icon', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@store']);
Route::get('/edit-social-icon/{edit_id}', ['as' => 'edit-social-icon', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@edit']);
Route::post('/update-social-icon', ['as' => 'update-social-icon', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@update']);
// Route::post('/show-social-icon', ['as' => 'show-social-icon', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@show']);
Route::post('/delete-social-icon', ['as' => 'delete-social-icon', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@destroy']);
Route::post('/update-one-social', ['as' => 'update-one-social', 'uses' => 'App\Http\Controllers\Admin\Footer\SocialIconController@updateOne']);

Route::get('/list-tollfree-number', ['as' => 'list-tollfree-number', 'uses' => 'App\Http\Controllers\Admin\Header\TollFreeController@index']);
Route::get('/add-tollfree-number', ['as' => 'add-tollfree-number', 'uses' => 'App\Http\Controllers\Admin\Header\TollFreeController@add']);
Route::post('/add-tollfree-number', ['as' => 'add-tollfree-number', 'uses' => 'App\Http\Controllers\Admin\Header\TollFreeController@store']);
Route::get('/edit-tollfree-number/{edit_id}', ['as' => 'edit-tollfree-number', 'uses' => 'App\Http\Controllers\Admin\Header\TollFreeController@edit']);
Route::post('/update-tollfree-number', ['as' => 'update-tollfree-number', 'uses' => 'App\Http\Controllers\Admin\Header\TollFreeController@update']);
Route::post('/show-tollfree-number', ['as' => 'show-tollfree-number', 'uses' => 'App\Http\Controllers\Admin\Header\TollFreeController@show']);
Route::post('/delete-tollfree-number', ['as' => 'delete-tollfree-number', 'uses' => 'App\Http\Controllers\Admin\Header\TollFreeController@destroy']);

Route::get('/list-website-logo', ['as' => 'list-website-logo', 'uses' => 'App\Http\Controllers\Admin\Header\WebsiteLogoController@index']);
Route::get('/add-website-logo', ['as' => 'add-website-logo', 'uses' => 'App\Http\Controllers\Admin\Header\WebsiteLogoController@add']);
Route::post('/add-website-logo', ['as' => 'add-website-logo', 'uses' => 'App\Http\Controllers\Admin\Header\WebsiteLogoController@store']);
Route::get('/edit-website-logo/{edit_id}', ['as' => 'edit-website-logo', 'uses' => 'App\Http\Controllers\Admin\Header\WebsiteLogoController@edit']);
Route::post('/update-website-logo', ['as' => 'update-website-logo', 'uses' => 'App\Http\Controllers\Admin\Header\WebsiteLogoController@update']);
Route::post('/show-website-logo', ['as' => 'show-website-logo', 'uses' => 'App\Http\Controllers\Admin\Header\WebsiteLogoController@show']);
Route::post('/delete-website-logo', ['as' => 'delete-website-logo', 'uses' => 'App\Http\Controllers\Admin\Header\WebsiteLogoController@destroy']);

Route::get('/list-header-vacancies', ['as' => 'list-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@index']);
Route::get('/add-header-vacancies', ['as' => 'add-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@add']);
Route::post('/add-header-vacancies', ['as' => 'add-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@store']);
Route::get('/edit-header-vacancies/{edit_id}', ['as' => 'edit-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@edit']);
Route::post('/update-header-vacancies', ['as' => 'update-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@update']);
Route::post('/show-header-vacancies', ['as' => 'show-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@show']);
Route::post('/delete-header-vacancies', ['as' => 'delete-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@destroy']);
Route::post('/update-one-vacancies', ['as' => 'update-one-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@updateOne']);

Route::get('/list-event', ['as' => 'list-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@index']);
Route::get('/add-event', ['as' => 'add-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@add']);
Route::post('/add-event', ['as' => 'add-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@store']);
Route::get('/edit-event/{edit_id}', ['as' => 'edit-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@edit']);
Route::post('/update-event', ['as' => 'update-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@update']);
Route::post('/show-event', ['as' => 'show-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@show']);
Route::post('/delete-event', ['as' => 'delete-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@destroy']);
Route::post('/update-one-event', ['as' => 'update-one-event', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\EventController@updateOne']);

Route::get('/list-list-capacity-training', ['as' => 'list-list-capacity-training', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\CapacityTrainingController@index']);
Route::get('/add-list-capacity-training', ['as' => 'add-list-capacity-training', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\CapacityTrainingController@add']);
Route::post('/add-list-capacity-training', ['as' => 'add-list-capacity-training', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\CapacityTrainingController@store']);
Route::get('/edit-list-capacity-training/{edit_id}', ['as' => 'edit-list-capacity-training', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\CapacityTrainingController@edit']);
Route::post('/update-list-capacity-training', ['as' => 'update-list-capacity-training', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\CapacityTrainingController@update']);
Route::post('/show-list-capacity-training', ['as' => 'show-list-capacity-training', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\CapacityTrainingController@show']);
Route::post('/delete-list-capacity-training', ['as' => 'delete-list-capacity-training', 'uses' => 'App\Http\Controllers\Admin\TrainingEvent\CapacityTrainingController@destroy']);
//=========Policies And legislation========
Route::get('/list-state-disaster-management-plan', ['as' => 'list-state-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@index']);
Route::get('/add-state-disaster-management-plan', ['as' => 'add-state-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@add']);
Route::post('/add-state-disaster-management-plan', ['as' => 'add-state-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@store']);
Route::get('/edit-state-disaster-management-plan/{edit_id}', ['as' => 'edit-state-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@edit']);
Route::post('/update-state-disaster-management-plan', ['as' => 'update-state-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@update']);
Route::post('/show-state-disaster-management-plan', ['as' => 'show-state-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@show']);
Route::post('/delete-state-disaster-management-plan', ['as' => 'delete-state-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@destroy']);
Route::post('/update-one-state_disaster', ['as' => 'update-one-state_disaster', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPlanController@updateOne']);

Route::get('/list-district-disaster-management-plan', ['as' => 'list-district-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@index']);
Route::get('/add-district-disaster-management-plan', ['as' => 'add-district-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@add']);
Route::post('/add-district-disaster-management-plan', ['as' => 'add-district-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@store']);
Route::get('/edit-district-disaster-management-plan/{edit_id}', ['as' => 'edit-district-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@edit']);
Route::post('/update-district-disaster-management-plan', ['as' => 'update-district-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@update']);
Route::post('/show-district-disaster-management-plan', ['as' => 'show-district-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@show']);
Route::post('/delete-district-disaster-management-plan', ['as' => 'delete-district-disaster-management-plan', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@destroy']);
Route::post('/update-one-disaster', ['as' => 'update-one-disaster', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DistrictDisasterManagementPlanController@updateOne']);

Route::get('/list-state-disaster-management-policy', ['as' => 'list-state-disaster-management-policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@index']);
Route::get('/add-state-disaster-management-policy', ['as' => 'add-state-disaster-management-policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@add']);
Route::post('/add-state-disaster-management-policy', ['as' => 'add-state-disaster-management-policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@store']);
Route::get('/edit-state-disaster-management-policy/{edit_id}', ['as' => 'edit-state-disaster-management-policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@edit']);
Route::post('/update-state-disaster-management-policy', ['as' => 'update-state-disaster-management-policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@update']);
Route::post('/show-state-disaster-management-policy', ['as' => 'show-state-disaster-management-policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@show']);
Route::post('/delete-state-disaster-management-policy', ['as' => 'delete-state-disaster-management-policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@destroy']);
Route::post('/update-one-disaster_policy', ['as' => 'update-one-disaster_policy', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\StateDisasterManagementPolicyController@updateOne']);

Route::get('/list-relevant-laws-and-regulations', ['as' => 'list-relevant-laws-and-regulations', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@index']);
Route::get('/add-relevant-laws-and-regulations', ['as' => 'add-relevant-laws-and-regulations', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@add']);
Route::post('/add-relevant-laws-and-regulations', ['as' => 'add-relevant-laws-and-regulations', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@store']);
Route::get('/edit-relevant-laws-and-regulations/{edit_id}', ['as' => 'edit-relevant-laws-and-regulations', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@edit']);
Route::post('/update-relevant-laws-and-regulations', ['as' => 'update-relevant-laws-and-regulations', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@update']);
Route::post('/show-relevant-laws-and-regulations', ['as' => 'show-relevant-laws-and-regulations', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@show']);
Route::post('/delete-relevant-laws-and-regulations', ['as' => 'delete-relevant-laws-and-regulations', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@destroy']);
Route::post('/update-one-relevant_law', ['as' => 'update-one-relevant_law', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\RelevantLawsRegulationsController@updateOne']);

Route::get('/list-disaster-management-act', ['as' => 'list-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@index']);
Route::get('/add-disaster-management-act', ['as' => 'add-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@add']);
Route::post('/add-disaster-management-act', ['as' => 'add-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@store']);
Route::get('/edit-disaster-management-act/{edit_id}', ['as' => 'edit-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@edit']);
Route::post('/update-disaster-management-act', ['as' => 'update-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@update']);
Route::post('/show-disaster-management-act', ['as' => 'show-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@show']);
Route::post('/delete-disaster-management-act', ['as' => 'delete-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@destroy']);
Route::post('/update-one-disaster-management-act', ['as' => 'update-one-disaster-management-act', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementActController@updateOne']);

Route::get('/list-disaster-management-guidelines', ['as' => 'list-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@index']);
Route::get('/add-disaster-management-guidelines', ['as' => 'add-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@add']);
Route::post('/add-disaster-management-guidelines', ['as' => 'add-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@store']);
Route::get('/edit-disaster-management-guidelines/{edit_id}', ['as' => 'edit-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@edit']);
Route::post('/update-disaster-management-guidelines', ['as' => 'update-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@update']);
Route::post('/show-disaster-management-guidelines', ['as' => 'show-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@show']);
Route::post('/delete-disaster-management-guidelines', ['as' => 'delete-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@destroy']);
Route::post('/update-one-disaster-management-guidelines', ['as' => 'update-one-disaster-management-guidelines', 'uses' => 'App\Http\Controllers\Admin\PoliciesAndGuidelines\DisasterManagementGuidelinesController@updateOne']);

//=======Research And Center==========

Route::get('/list-document-publications', ['as' => 'list-document-publications', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\DocumentPublicationsController@index']);
Route::get('/add-document-publications', ['as' => 'add-document-publications', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\DocumentPublicationsController@add']);
Route::post('/add-document-publications', ['as' => 'add-document-publications', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\DocumentPublicationsController@store']);
Route::get('/edit-document-publications/{edit_id}', ['as' => 'edit-document-publications', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\DocumentPublicationsController@edit']);
Route::post('/update-document-publications', ['as' => 'update-document-publications', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\DocumentPublicationsController@update']);
Route::post('/show-document-publications', ['as' => 'show-document-publications', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\DocumentPublicationsController@show']);
Route::post('/delete-document-publications', ['as' => 'delete-document-publications', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\DocumentPublicationsController@destroy']);

Route::get('/list-training-workshop', ['as' => 'list-training-workshop', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\TrainingWorkshopController@index']);
Route::get('/add-training-workshop', ['as' => 'add-training-workshop', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\TrainingWorkshopController@add']);
Route::post('/add-training-workshop', ['as' => 'add-training-workshop', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\TrainingWorkshopController@store']);
Route::get('/edit-training-workshop/{edit_id}', ['as' => 'edit-training-workshop', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\TrainingWorkshopController@edit']);
Route::post('/update-training-workshop', ['as' => 'update-training-workshop', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\TrainingWorkshopController@update']);
Route::post('/show-training-workshop', ['as' => 'show-training-workshop', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\TrainingWorkshopController@show']);
Route::post('/delete-training-workshop', ['as' => 'delete-training-workshop', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\TrainingWorkshopController@destroy']);

//======News And Events=======
Route::get('/list-success-stories', ['as' => 'list-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@index']);
Route::get('/add-success-stories', ['as' => 'add-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@add']);
Route::post('/add-success-stories', ['as' => 'add-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@store']);
Route::get('/edit-success-stories/{edit_id}', ['as' => 'edit-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@edit']);
Route::post('/update-success-stories', ['as' => 'update-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@update']);
Route::post('/show-success-stories', ['as' => 'show-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@show']);
Route::post('/delete-success-stories', ['as' => 'delete-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@destroy']);
Route::post('/update-one-success-stories', ['as' => 'update-one-success-stories', 'uses' => 'App\Http\Controllers\Admin\NewsAndEvents\SuccessStoriesController@updateOne']);

Route::get('/list-gallery-category', ['as' => 'list-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@index']);
Route::get('/add-gallery-category', ['as' => 'add-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@add']);
Route::post('/add-gallery-category', ['as' => 'add-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@store']);
Route::get('/edit-gallery-category/{edit_id}', ['as' => 'edit-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@edit']);
Route::post('/update-gallery-category', ['as' => 'update-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@update']);
Route::post('/show-gallery-category', ['as' => 'show-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@show']);
Route::post('/delete-gallery-category', ['as' => 'delete-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@destroy']);
Route::post('/update-one-gallery-category', ['as' => 'update-one-gallery-category', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryCategoryController@updateOne']);

Route::get('/list-gallery', ['as' => 'list-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@index']);
Route::get('/add-gallery', ['as' => 'add-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@add']);
Route::post('/add-gallery', ['as' => 'add-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@store']);
Route::get('/edit-gallery/{edit_id}', ['as' => 'edit-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@edit']);
Route::post('/update-gallery', ['as' => 'update-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@update']);
Route::post('/show-gallery', ['as' => 'show-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@show']);
Route::post('/delete-gallery', ['as' => 'delete-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@destroy']);
Route::post('/update-one-gallery', ['as' => 'update-one-gallery', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\GalleryController@updateOne']);

Route::get('/list-video', ['as' => 'list-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@index']);
Route::get('/add-video', ['as' => 'add-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@add']);
Route::post('/add-video', ['as' => 'add-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@store']);
Route::get('/edit-video/{edit_id}', ['as' => 'edit-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@edit']);
Route::post('/update-video', ['as' => 'update-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@update']);
Route::post('/show-video', ['as' => 'show-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@show']);
Route::post('/delete-video', ['as' => 'delete-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@destroy']);
Route::post('/update-one-video', ['as' => 'update-one-video', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\VideoController@updateOne']);

Route::get('/list-map-lat-lons', ['as' => 'list-map-lat-lons', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\MapLatLonController@index']);
Route::get('/add-map-lot-lons', ['as' => 'add-map-lot-lons', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\MapLatLonController@add']);
Route::post('/add-map-lot-lons', ['as' => 'add-map-lot-lons', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\MapLatLonController@store']);
Route::get('/edit-map-lot-lons/{edit_id}', ['as' => 'edit-map-lot-lons', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\MapLatLonController@edit']);
Route::post('/update-map-lot-lons', ['as' => 'update-map-lot-lons', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\MapLatLonController@update']);
Route::post('/show-map-lot-lons', ['as' => 'show-map-lot-lons', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\MapLatLonController@show']);
Route::post('/delete-map-lot-lons', ['as' => 'delete-map-lot-lons', 'uses' => 'App\Http\Controllers\Admin\ResourceCenter\MapLatLonController@destroy']);

// =======Contact Us==========

Route::get('/list-contact-suggestion', ['as' => 'list-contact-suggestion', 'uses' => 'App\Http\Controllers\ContactUs\ContactUsController@index']);
Route::get('/add-contact-suggestion', ['as' => 'add-contact-suggestion', 'uses' => 'App\Http\Controllers\ContactUs\ContactUsController@add']);
Route::post('/add-contact-suggestion', ['as' => 'add-contact-suggestion', 'uses' => 'App\Http\Controllers\ContactUs\ContactUsController@store']);
Route::post('/edit-contact-suggestion', ['as' => 'edit-contact-suggestion', 'uses' => 'App\Http\Controllers\ContactUs\ContactUsController@edit']);
Route::post('/update-contact-suggestion', ['as' => 'update-contact-suggestion', 'uses' => 'App\Http\Controllers\ContactUs\ContactUsController@update']);
Route::post('/show-contact-suggestion', ['as' => 'show-contact-suggestion', 'uses' => 'App\Http\Controllers\ContactUs\ContactUsController@show']);
Route::post('/delete-contact-suggestion', ['as' => 'delete-contact-suggestion', 'uses' => 'App\Http\Controllers\ContactUs\ContactUsController@destroy']);
// Route::post('/update-one-contact-suggestion', ['as' => 'update-one-gallery', 'uses' => 'App\Http\Controllers\NewsAndEvents\GalleryController@updateOne']);

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

//=====Footer Route======
Route::get('/list-important-link', ['as' => 'list-important-link', 'uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@index']);
Route::get('/add-important-link', ['as' => 'add-important-link', 'uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@add']);
Route::post('/add-important-link', ['as' => 'add-important-link', 'uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@store']);
Route::get('/edit-important-link/{edit_id}', ['as' => 'edit-important-link', 'uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@edit']);
Route::post('/update-important-link', ['as' => 'update-important-link','uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@update']);
Route::post('/show-important-link', ['as' => 'show-important-link', 'uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@show']);
Route::post('/delete-important-link', ['as' => 'delete-important-link', 'uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@destroy']);
Route::post('/update-one-important-link', ['as' => 'update-one-important-link', 'uses' => 'App\Http\Controllers\Admin\Footer\FooterImportantLinksController@updateOne']);

Route::get('/list-tweeter-feed', ['as' => 'list-tweeter-feed', 'uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@index']);
Route::get('/add-tweeter-feed', ['as' => 'add-tweeter-feed', 'uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@add']);
Route::post('/add-tweeter-feed', ['as' => 'add-tweeter-feed', 'uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@store']);
Route::get('/edit-tweeter-feed/{edit_id}', ['as' => 'edit-tweeter-feed', 'uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@edit']);
Route::post('/update-tweeter-feed', ['as' => 'update-tweeter-feed','uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@update']);
Route::post('/show-tweeter-feed', ['as' => 'show-tweeter-feed', 'uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@show']);
Route::post('/delete-tweeter-feed', ['as' => 'delete-tweeter-feed', 'uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@destroy']);
Route::post('/update-one-tweeter-feed', ['as' => 'update-one-tweeter-feed', 'uses' => 'App\Http\Controllers\Admin\Footer\TweeterFeedsController@updateOne']);

Route::get('/list-privacy-policy', ['as' => 'list-privacy-policy', 'uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@index']);
Route::get('/add-privacy-policy', ['as' => 'add-privacy-policy', 'uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@add']);
Route::post('/add-privacy-policy', ['as' => 'add-privacy-policy', 'uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@store']);
Route::get('/edit-privacy-policy/{edit_id}', ['as' => 'edit-privacy-policy', 'uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@edit']);
Route::post('/update-privacy-policy', ['as' => 'update-privacy-policy','uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@update']);
Route::post('/show-privacy-policy', ['as' => 'show-privacy-policy', 'uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@show']);
Route::post('/delete-privacy-policy', ['as' => 'delete-privacy-policy', 'uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@destroy']);
// Route::post('/update-one-privacy-policy', ['as' => 'update-one-privacy-policy', 'uses' => 'App\Http\Controllers\Admin\Footer\PolicyPrivacyController@updateOne']);

Route::get('/list-terms-conditions', ['as' => 'list-terms-conditions', 'uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@index']);
Route::get('/add-terms-conditions', ['as' => 'add-terms-conditions', 'uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@add']);
Route::post('/add-terms-conditions', ['as' => 'add-terms-conditions', 'uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@store']);
Route::get('/edit-terms-conditions/{edit_id}', ['as' => 'edit-terms-conditions', 'uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@edit']);
Route::post('/update-terms-conditions', ['as' => 'update-terms-conditions','uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@update']);
Route::post('/show-terms-conditions', ['as' => 'show-terms-conditions', 'uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@show']);
Route::post('/delete-terms-conditions', ['as' => 'delete-terms-conditions', 'uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@destroy']);
// Route::post('/update-one-terms-conditions', ['as' => 'update-one-terms-conditions', 'uses' => 'App\Http\Controllers\Admin\Footer\TermsConditionController@updateOne']);

Route::get('/list-contact-department', ['as' => 'list-contact-department', 'uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@index']);
Route::get('/add-contact-department', ['as' => 'add-contact-department', 'uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@add']);
Route::post('/add-contact-department', ['as' => 'add-contact-department', 'uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@store']);
Route::get('/edit-contact-department/{edit_id}', ['as' => 'edit-contact-department', 'uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@edit']);
Route::post('/update-contact-department', ['as' => 'update-contact-department','uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@update']);
Route::post('/show-contact-department', ['as' => 'show-contact-department', 'uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@show']);
Route::post('/delete-contact-department', ['as' => 'delete-contact-department', 'uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@destroy']);
Route::post('/update-one-contact-department', ['as' => 'update-one-contact-department', 'uses' => 'App\Http\Controllers\Admin\Footer\WebsiteContactDepartmentController@updateOne']);
//====Header Vacancies=====
Route::get('/list-header-vacancies', ['as' => 'list-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@index']);
Route::get('/add-header-vacancies', ['as' => 'add-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@add']);
Route::post('/add-header-vacancies', ['as' => 'add-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@store']);
Route::get('/edit-header-vacancies/{edit_id}', ['as' => 'edit-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@edit']);
Route::post('/update-header-vacancies', ['as' => 'update-header-vacancies','uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@update']);
Route::post('/show-header-vacancies', ['as' => 'show-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@show']);
Route::post('/delete-header-vacancies', ['as' => 'delete-header-vacancies', 'uses' => 'App\Http\Controllers\Admin\Header\VacanciesHeaderController@destroy']);
Route::post('/update-one-header-vacancies', ['as' => 'update-one-header-vacancies', 'uses' => 'App\Http\Controllers\Footer\FooterImportantLinksController@updateOne']);

Route::get('/list-header-rti', ['as' => 'list-header-rti', 'uses' => 'App\Http\Controllers\Admin\Header\RTIController@index']);
Route::get('/add-header-rti', ['as' => 'add-header-rti', 'uses' => 'App\Http\Controllers\Admin\Header\RTIController@add']);
Route::post('/add-header-rti', ['as' => 'add-header-rti', 'uses' => 'App\Http\Controllers\Admin\Header\RTIController@store']);
Route::get('/edit-header-rti/{edit_id}', ['as' => 'edit-header-rti', 'uses' => 'App\Http\Controllers\Admin\Header\RTIController@edit']);
Route::post('/update-header-rti', ['as' => 'update-header-rti','uses' => 'App\Http\Controllers\Admin\Header\RTIController@update']);
Route::post('/show-header-rti', ['as' => 'show-header-rti', 'uses' => 'App\Http\Controllers\Admin\Header\RTIController@show']);
Route::post('/delete-header-rti', ['as' => 'delete-header-rti', 'uses' => 'App\Http\Controllers\Admin\Header\RTIController@destroy']);
Route::post('/update-one-rti', ['as' => 'update-one-rti', 'uses' => 'App\Http\Controllers\Admin\Header\RTIController@updateOne']);

Route::get('/db-backup', ['as' => 'db-backup', 'uses' => 'App\Http\Controllers\DBBackup\DBBackupController@downloadBackup']);

// Route::get('/asd', [TaskController::class, 'index']);
// Route::post('/tasks', [TaskController::class, 'store']);
// Route::put('/tasks/{task}', [TaskController::class, 'update']);
// Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

Route::get('/log-out', ['as' => 'log-out', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@logout']);

});
