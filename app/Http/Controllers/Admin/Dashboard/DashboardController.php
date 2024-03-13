<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Services\DashboardServices;
use App\Models\ {
    // Roles,
    Permissions,
    User,
    // IncidentType,
    RTI,
    VacanciesHeader,
    // MainMenus,
    // MainSubMenus,
    // Marquee,
    // Slider,
    // DisasterManagementWebPortal,
    // DisasterManagementNews,
    // EmergencyContact,
    DepartmentInformation,
    // Weather,
    DisasterForcast,
    // DisasterManagementPortal,
    // ObjectiveGoals,
    // StateDisasterManagementAuthority,
    // DynamicWebPages,
    // HazardVulnerability,
    // EarlyWarningSystem,
    // CapacityTraining,
    // PublicAwarenessEducation,
    // StateEmergencyOperationsCenter,
    // DistrictEmergencyOperationsCenter,
    // EmergencyContactNumbers,
    // EvacuationPlans,
    // ReliefMeasuresResources,
    // SearchRescueTeams,
    // ReportIncidentCrowdsourcing,
    // VolunteerCitizenSupport,
    // CitizenFeedbackSuggestion,
    // ReportIncidentModal,
    // CitizenVolunteerModal,
    // StateDisasterManagementPlan,
    // DistrictDisasterManagementPlan,
    // StateDisasterManagementPolicy,
    // RelevantLawsRegulation,
    // Documentspublications,
    // SuccessStories,
    // GalleryCategory,
    Gallery,
    Video,
    // TrainingMaterialsWorkshops,
    // Contact,
    Event,
    // Metadata,
    
    // FooterImportantLinks,
    // WebsiteContact



};
use Validator;

class DashboardController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        // $this->service = new DashboardServices();
    }

    public function index()
    {
        $return_data = array();
        $dashboard_data = Permissions::where("is_active",'=',true)->get()->toArray();

        return view('admin.pages.dashboard');
    }



}