<?php
namespace App\Http\Controllers\Api\Master;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	Labour,
    Project,
    ProjectUser,
    ProjectLabours
};

class ProjectController extends Controller
{

    public function getAllProject(Request $request){
        try {
            $user = Auth::user()->id;
            // dd($user);
            $project = ProjectUser::leftJoin('projects', 'project_users.project_id', '=', 'projects.id')
            ->leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
            ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
            ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
              ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')
              ->where('project_users.user_id', $user)
              ->where('projects.is_active', true)
              ->when($request->has('project_name'), function($query) use ($request) {
                $query->where('projects.project_name', 'like', '%' . $request->project_name . '%');
            })             
              ->select(
                  'projects.id',
                  'projects.project_name',
                  'projects.description',
                  'state_projects.name as state',
                  'district_projects.name as district',
                  'taluka_projects.name as taluka',
                  'village_projects.name as village',
				  'projects.start_date',
				  'projects.end_date',
				  'projects.latitude',
				  'projects.longitude',
                  'projects.start_date',
                  'projects.end_date',
                  'projects.latitude',
                  'projects.longitude',
                
              )->get();
            //   dd($project);
            return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $project], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

   
    public function filterDataProjectsLaboursMap(Request $request){
        try {
            $user = Auth::user()->id;
            $labourQuery = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->select(
                    'labour.id',
                    'labour.full_name',
                    'labour.date_of_birth',
                    'gender_labour.gender_name as gender_name',
                    'district_labour.name as district_id',
                    'taluka_labour.name as taluka_id',
                    'village_labour.name as village_id',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'labour.aadhar_image',
                    'labour.mgnrega_image',
                    'labour.profile_image',
                );
    
            $projectQuery = ProjectUser::leftJoin('projects', 'project_users.project_id', '=', 'projects.id')
                ->leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
                ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
                ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
                ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')
                ->where('project_users.user_id', $user)
                ->where('projects.is_active', true)
                ->select(
                    'projects.id',
                    'projects.project_name',
                    'projects.description',
                    'state_projects.name as state',
                    'district_projects.name as district',
                    'taluka_projects.name as taluka',
                    'village_projects.name as village',
                    'projects.start_date',
                    'projects.end_date',
                    'projects.latitude',
                    'projects.longitude'
                );
    
            // Apply filters for labor data
            if ($request->has('mgnrega_card_id')) {
                $labourQuery->where('labour.mgnrega_card_id', 'like', '%' . $request->input('mgnrega_card_id') . '%');
            }
    
            // Apply filters for project data
            if ($request->has('project_name')) {
                $projectQuery->where('projects.project_name', 'LIKE', '%'.$request->input('project_name').'%');
            }
            
            // Fetch data
            $labourData = $labourQuery->get();
            $projectData = $projectQuery->get();
            // Check if mgnrega_card_id filter applied and adjust response accordingly
            if ($request->has('mgnrega_card_id')) {
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Filtered labour data retrieved successfully', 
                    'labour_data' => $labourData
                ], 200);
            }
            elseif ($request->has('project_name')) {
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Filtered project data retrieved successfully', 
                    'project_data' => $projectData
                ], 200);
            }
            else {
                // Fetch project data only if no mgnrega_card_id filter applied
                // $projectData = $projectQuery->get();
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Filtered data retrieved successfully', 
                    'labour_data' => $labourData,
                    'project_data' => $projectData
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getAllProjectLatLong(Request $request)
    {
        try {
            $user = Auth::user()->id;
            $userLatitude = $request->latitude; // Latitude of the user
            $userLongitude = $request->longitude; // Longitude of the user
            $distanceInKm = 5; // Distance in kilometers
            // dd($userLatitude);
        $latLongArr= $this->getLatitudeLongitude($userLatitude,$userLongitude, $distanceInKm);
        $latN = $latLongArr['latN'];
        $latS = $latLongArr['latS'];
        $lonE = $latLongArr['lonE'];
        $lonW = $latLongArr['lonW'];
			
            // Haversine formula to calculate distance
            $project = ProjectUser::leftJoin('projects', 'project_users.project_id', '=', 'projects.id')
                ->leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
                ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
                ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
                ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')
                // ->where('project_users.user_id')
                ->where('projects.is_active', true)
               
                // ->when($request->has('latitude'), function($query) use ($request) {
                //     $query->where('projects.latitude', '<=', $latN)
                //     ->where('projects.latitude', '>=', $latS)
                //     ->where('projects.longitude', '<=', $lonE)
                //     ->where('projects.longitude', '>=', $lonW);
                // })  
                ->when($request->has('latitude'), function($query) use ($request, $latN, $latS, $lonE, $lonW) {
                    $query->where('projects.latitude', '<=', $latN)
                        ->where('projects.latitude', '>=', $latS)
                        ->where('projects.longitude', '<=', $lonE)
                        ->where('projects.longitude', '>=', $lonW);
                })
                
                ->when($request->has('project_name'), function($query) use ($request) {
                    $query->where('projects.project_name', 'like', '%' . $request->project_name . '%');
                })             
                ->select(
                    'projects.id',
                    'projects.project_name',
                    'projects.description',
                    'state_projects.name as state',
                    'district_projects.name as district',
                    'taluka_projects.name as taluka',
                    'village_projects.name as village',
                    'projects.start_date',
                    'projects.end_date',
                    'projects.latitude',
                    'projects.longitude'
                )
                ->get();
                
            return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $project], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getLatitudeLongitude($latitude,$longitude, $distanceInKm){
        $d = 0.621371*$distanceInKm; // 15 km in miles
        $r = 3959; //earth's radius in miles

        $latLongArr = array();
        
       

        $latN = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
                + cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(0))));

        $latS = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
                + cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(180))));

        $lonE = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(90))
                * sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
                - sin(deg2rad($latitude)) * sin(deg2rad($latN))));

        $lonW = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(270))
                * sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
                - sin(deg2rad($latitude)) * sin(deg2rad($latN))));

        $latLongArr = 
        [
            'pincodeLatitude' => $latitude,
            'pincodeLongitude' => $longitude,
            'latN' => $latN,
            'latS' => $latS,
            'lonE' => $lonE,
            'lonW' => $lonW
        ];
        return $latLongArr;
        }
        
    
    
}


