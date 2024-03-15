<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Project;

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
}


