<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	Labour,
    Project
};

class ProjectController extends Controller
{

    public function getAllProject(){
        try {
            // $project = Project::all();
            $project = Project::leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
            ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
            ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
              ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')
            //   ->where('gender.is_active', true)
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
            return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $project], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function filterProjectList(Request $request){
        try {
            $query =  Project::leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
                ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
                ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
                ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')
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
    
            // Apply filters if provided
            if ($request->has('project_name')) {
                $query->where('projects.project_name', 'LIKE', '%'.$request->input('project_name').'%');
            }
            
            $data_output = $query->get();
    
            return response()->json(['status' => 'success', 'message' => 'Filtered data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    

    public function filterData(Request $request){
        try {
            $labourQuery = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
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
    
            $projectQuery = Project::leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
                ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
                ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
                ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')
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
                    'labour_data' => $labourData
                ], 200);
            }
            else {
                // Fetch project data only if no mgnrega_card_id filter applied
                $projectData = $projectQuery->get();
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
    
    
    
}


