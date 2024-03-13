<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissions;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Dashboard',
            //         'url' => 'dashboard',
            //         'permission_name' => 'Dashboard',
            //     ]);
            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Landing-Content',
                    'url' => 'list-landing-content',
                    'permission_name' => 'Landing-Content',
                ]);
            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Landing-Slider',
                    'url' => 'list-landing-slide',
                    'permission_name' => 'Landing-Slider',
                ]);
                // Permissions::create(
                // [
                //     'created_at' => \Carbon\Carbon::now(),
                //     'updated_at' => \Carbon\Carbon::now(),
                //     'route_name' => 'Upload Document',
                //     'url' => 'upload-document',
                //     'permission_name' => 'Upload-Document',
                // ]);
                Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'List Report an Incident : Crowdsourcing',
                    'url' => 'list-incident-modal-info',
                    'permission_name' => 'List Report an Incident : Crowdsourcing',
                ]);

            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Role',
                    'url' => 'list-role',
                    'permission_name' => 'Role',
                ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Incident Type',
            //         'url' => 'list-incident-type',
            //         'permission_name' => 'Incident Type',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Vacancies',
            //         'url' => 'list-header-vacancies',
            //         'permission_name' => 'Vacancies',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'RTI',
            //         'url' => 'list-header-rti',
            //         'permission_name' => 'RTI',
            //     ]);
            //     Permissions::create(
            //         [
            //             'created_at' => \Carbon\Carbon::now(),
            //             'updated_at' => \Carbon\Carbon::now(),
            //             'route_name' => 'Toll Free Number',
            //             'url' => 'list-tollfree-number',
            //             'permission_name' => 'Toll Free Number',
            //         ]);
            //         Permissions::create(
            //             [
            //                 'created_at' => \Carbon\Carbon::now(),
            //                 'updated_at' => \Carbon\Carbon::now(),
            //                 'route_name' => 'Website Logo',
            //                 'url' => 'list-website-logo',
            //                 'permission_name' => 'Website Logo',
            //             ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Main Menu',
            //         'url' => 'list-main-menu',
            //         'permission_name' => 'Main Menu List',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Sub Menu',
            //         'url' => 'list-sub-menu',
            //         'permission_name' => 'Sub Menu List',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'News Bar',
            //         'url' => 'list-marquee',
            //         'permission_name' => 'News Bar',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Slider',
            //         'url' => 'list-slide',
            //         'permission_name' => 'Slider',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Welcome Section',
            //         'url' => 'list-disaster-management-web-portal',
            //         'permission_name' => 'Welcome Section',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Disaster Management News',
            //         'url' => 'list-disaster-management-news',
            //         'permission_name' => 'Disaster Management News',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Emergency Contact',
            //         'url' => 'list-emergency-contact',
            //         'permission_name' => 'Emergency Contact',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Department',
            //         'url' => 'list-department-information',
            //         'permission_name' => 'Department',
            //     ]);
          
            // // Permissions::create(
            // //     [
            // //         'created_at' => \Carbon\Carbon::now(),
            // //         'updated_at' => \Carbon\Carbon::now(),
            // //         'route_name' => 'Weather',
            // //         'url' => 'list-weather',
            // //         'permission_name' => 'Weather',
            // //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Disaster Forecast',
            //         'url' => 'list-disasterforcast',
            //         'permission_name' => 'Disaster Forecast',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Disaster Management Portal',
            //         'url' => 'list-disastermanagementportal',
            //         'permission_name' => 'Disaster Management Portal',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Objective Goals',
            //         'url' => 'list-objectivegoals',
            //         'permission_name' => 'Objective Goals',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'State Disaster Management Authority',
            //         'url' => 'list-statedisastermanagementauthority',
            //         'permission_name' => 'State Disaster Management Authority',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Dynamic Pages',
            //         'url' => 'list-dynamic-page',
            //         'permission_name' => 'Dynamic Pages',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Hazard and Vulnerability',
            //         'url' => 'list-hazard-vulnerability-assessment',
            //         'permission_name' => 'Hazard and Vulnerability',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Early Warning System',
            //         'url' => 'list-early-warning-system',
            //         'permission_name' => 'Early Warning System',
            //     ]);
             
                            
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Awareness And Education',
            //         'url' => 'list-public-awareness-and-education',
            //         'permission_name' => 'Awareness And Education',
            //     ]);
            //     Permissions::create(
            //         [
            //             'created_at' => \Carbon\Carbon::now(),
            //             'updated_at' => \Carbon\Carbon::now(),
            //             'route_name' => 'Govt Hospitals',
            //             'url' => 'list-govt-hospitals',
            //             'permission_name' => 'Govt Hospitals',
            //         ]);
    

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'State Emergency Operations Center (EOC)',
            //         'url' => 'list-state-emergency-operations-center',
            //         'permission_name' => 'State Emergency Operations Center (EOC)',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'District Emergency Operations Center (DEOC)',
            //         'url' => 'list-district-emergency-operations-center',
            //         'permission_name' => 'District Emergency Operations Center (DEOC)',
            //     ]);
            //     Permissions::create(
            //         [
            //             'created_at' => \Carbon\Carbon::now(),
            //             'updated_at' => \Carbon\Carbon::now(),
            //             'route_name' => 'Emergency Contact Numbers',
            //             'url' => 'edit-emergency-contact-numbers',
            //             'permission_name' => 'Emergency Contact Numbers',
            //         ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Emergency Contact Numbers',
            //         'url' => 'July-numbers',
            //         'permission_name' => 'Emergency Contact Numbers',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Evacuation Plans',
            //         'url' => 'list-evacuation-plans',
            //         'permission_name' => 'Evacuation Plans',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Relief Measures Resources',
            //         'url' => 'list-relief-measures-resources',
            //         'permission_name' => 'Relief Measures Resources',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Search Rescue Teams',
            //         'url' => 'list-search-rescue-teams',
            //         'permission_name' => 'Search Rescue Teams',
            //     ]);
            
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'List Report an Incident : Crowdsourcing',
            //         'url' => 'list-incident-modal-info',
            //         'permission_name' => 'List Report an Incident : Crowdsourcing',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'List Be A Volunteer : Citizen Support',
            //         'url' => 'list-volunteer-modal-info',
            //         'permission_name' => 'List Be A Volunteer : Citizen Support',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'State Disaster Management Plan',
            //         'url' => 'list-state-disaster-management-plan',
            //         'permission_name' => 'State Disaster Management Plan',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'District Disaster Management Plan',
            //         'url' => 'list-district-disaster-management-plan',
            //         'permission_name' => 'District Disaster Management Plan',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'State Disaster Management Policy',
            //         'url' => 'list-state-disaster-management-policy',
            //         'permission_name' => 'State Disaster Management Policy',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Relevant-Laws-And-Regulations',
            //         'url' => 'list-relevant-laws-and-regulations',
            //         'permission_name' => 'Relevant-Laws-And-Regulations',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Disaster-Management-Act',
            //         'url' => 'list-disaster-management-act',
            //         'permission_name' => 'Disaster-Management-Act',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Disaster-Management-Guidelines',
            //         'url' => 'list-disaster-management-guidelines',
            //         'permission_name' => 'Disaster-Management-Guidelines',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Documents And Publications',
            //         'url' => 'list-document-publications',
            //         'permission_name' => 'Documents And Publications',
            //     ]);
                
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'MAP GIS Data',
            //         'url' => 'list-map-lat-lons',
            //         'permission_name' => 'MAP GIS Data',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Success Stories',
            //         'url' => 'list-success-stories',
            //         'permission_name' => 'Success Stories',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Category Gallery',
            //         'url' => 'list-gallery-category',
            //         'permission_name' => 'Category Gallery',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Gallery',
            //         'url' => 'list-gallery',
            //         'permission_name' => 'Gallery',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Video',
            //         'url' => 'list-video',
            //         'permission_name' => 'Video',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Training Materials',
            //         'url' => 'list-training-workshop',
            //         'permission_name' => 'Training Materials',
            //     ]);
            //     Permissions::create(
            //         [
            //             'created_at' => \Carbon\Carbon::now(),
            //             'updated_at' => \Carbon\Carbon::now(),
            //             'route_name' => 'Capacity training',
            //             'url' => 'list-list-capacity-training',
            //             'permission_name' => 'Capacity training',
            //         ]); 
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Contact Us',
            //         'url' => 'list-contact-suggestion',
            //         'permission_name' => 'Contact Us',
            //     ]);

            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Event',
            //         'url' => 'list-event',
            //         'permission_name' => 'Event',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Metadata',
            //         'url' => 'list-metadata',
            //         'permission_name' => 'Metadata',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'User',
            //         'url' => 'list-users',
            //         'permission_name' => 'User',
            //     ]);
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Footer Link',
            //         'url' => 'list-important-link',
            //         'permission_name' => 'Footer Link',
            //     ]);   
            //     Permissions::create(
            //         [
            //             'created_at' => \Carbon\Carbon::now(),
            //             'updated_at' => \Carbon\Carbon::now(),
            //             'route_name' => 'Twitter Link',
            //             'url' => 'list-tweeter-feed',
            //             'permission_name' => 'Twitter Link',
            //         ]);  
            //         Permissions::create(
            //             [
            //                 'created_at' => \Carbon\Carbon::now(),
            //                 'updated_at' => \Carbon\Carbon::now(),
            //                 'route_name' => 'Privacy Policy',
            //                 'url' => 'list-privacy-policy',
            //                 'permission_name' => 'Privacy Policy',
            //             ]);  
            //         Permissions::create(
            //             [
            //                 'created_at' => \Carbon\Carbon::now(),
            //                 'updated_at' => \Carbon\Carbon::now(),
            //                 'route_name' => 'Terms and Conditions',
            //                 'url' => 'list-terms-conditions',
            //                 'permission_name' => 'Terms and Conditions',
            //             ]);             
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Social Icon',
            //         'url' => 'list-social-icon',
            //         'permission_name' => 'Social Icon',
            //     ]);
            //     Permissions::create(
            //         [
            //             'created_at' => \Carbon\Carbon::now(),
            //             'updated_at' => \Carbon\Carbon::now(),
            //             'route_name' => 'Website Conatct',
            //             'url' => 'list-website-contact',
            //             'permission_name' => 'Website Conatct',
            //         ]);
        


            
    }
}
