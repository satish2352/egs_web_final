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
        foreach ($dashboard_data as $value) {

            if($value['url'] == 'list-users') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = User::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }

            // if($value['url'] == 'list-role') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = Roles::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-incident-type') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = IncidentType::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }
            if($value['url'] == 'list-header-vacancies') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = VacanciesHeader::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }
            
            if($value['url'] == 'list-header-rti') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = RTI::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }

            // if($value['url'] == 'list-main-menu') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = MainMenus::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-sub-menu') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = MainSubMenus::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-marquee') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = Marquee::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-slide') {       
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name']; 
            //     $roles = Slider::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-disaster-management-web-portal') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = DisasterManagementWebPortal::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }
          
            
            // if($value['url'] == 'list-disaster-management-news') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = DisasterManagementNews::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-emergency-contact') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = EmergencyContact::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            if($value['url'] == 'list-department-information') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = DepartmentInformation::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }

            // if($value['url'] == 'list-weather') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = Weather::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            if($value['url'] == 'list-disasterforcast') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = DisasterForcast::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }

            // if($value['url'] == 'list-disastermanagementportal') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = DisasterManagementPortal::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-objectivegoals') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = ObjectiveGoals::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            
            // if($value['url'] == 'list-statedisastermanagementauthority') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = StateDisasterManagementAuthority::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

             
            // if($value['url'] == 'list-dynamic-page') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = DynamicWebPages::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-hazard-vulnerability-assessment') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = HazardVulnerability::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-early-warning-system') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = EarlyWarningSystem::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-list-capacity-training') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = CapacityTraining::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-public-awareness-and-education') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = PublicAwarenessEducation::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-state-emergency-operations-center') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = StateEmergencyOperationsCenter::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-district-emergency-operations-center') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = DistrictEmergencyOperationsCenter::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            
            // if($value['url'] == 'edit-emergency-contact-numbers') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = EmergencyContactNumbers::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'July-numbers') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = Marquee::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-evacuation-plans') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = EvacuationPlans::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-relief-measures-resources') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = ReliefMeasuresResources::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-search-rescue-teams') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = SearchRescueTeams::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            
            // if($value['url'] == 'list-report-crowdsourcing') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = ReportIncidentCrowdsourcing::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

              
            // if($value['url'] == 'list-volunteer-citizen-support') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = VolunteerCitizenSupport::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

                 
            // if($value['url'] == 'list-citizen-feedback-and-suggestion') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = CitizenFeedbackSuggestion::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

                  
            // if($value['url'] == 'list-incident-modal-info') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = ReportIncidentModal::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-volunteer-modal-info') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = CitizenVolunteerModal::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-feedback-modal-info') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = CitizenFeedbackSuggestionModal::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            
            // if($value['url'] == 'list-state-disaster-management-plan') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = StateDisasterManagementPlan::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-district-disaster-management-plan') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = DistrictDisasterManagementPlan::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-state-disaster-management-policy') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = StateDisasterManagementPolicy::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            
            // if($value['url'] == 'list-relevant-laws-and-regulations') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = RelevantLawsRegulation::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            
            // if($value['url'] == 'list-document-publications') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = Documentspublications::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

              
            // if($value['url'] == 'list-success-stories') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = SuccessStories::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-gallery-category') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = GalleryCategory::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            if($value['url'] == 'list-gallery') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = Gallery::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }

            
            if($value['url'] == 'list-video') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = Video::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }

             
            // if($value['url'] == 'list-training-workshop') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = TrainingMaterialsWorkshops::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

              
            // if($value['url'] == 'list-contact-suggestion') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = Contact::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

                 
            if($value['url'] == 'list-event') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $roles = Event::all();
                $data_dashboard['count'] = $roles->count();
                array_push($return_data, $data_dashboard);
            }

                 
            // if($value['url'] == 'list-metadata') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = Metadata::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

        

            // if($value['url'] == 'list-important-link') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = FooterImportantLinks::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }

            // if($value['url'] == 'list-website-contact') {
            //     $data_dashboard['url'] =  $value['url'];
            //     $data_dashboard['permission_name'] =  $value['permission_name'];
            //     $roles = WebsiteContact::all();
            //     $data_dashboard['count'] = $roles->count();
            //     array_push($return_data, $data_dashboard);
            // }
        }

        return view('admin.pages.dashboard',compact('return_data'));
    }



}