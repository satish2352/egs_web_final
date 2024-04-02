<?php

namespace App\Http\Controllers\Admin\Gramsevak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Gramsevak\GramsevakServices;
use App\Models\ {
    Roles,
    Permissions,
    TblArea,
    User,
    Labour,
    LabourAttendanceMark,
    Registrationstatus,
    Reasons,
    HistoryModel,
    Project
};
use Validator;
use session;
use Config;

class GramsevakController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->service = new GramsevakServices();
    }

    public function index()
    {

        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');
        
        $district_data = TblArea::where('parent_id', '2')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

        $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

                    $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                    ->where('users.id', $sess_user_id)
                    ->first();
    
                $utype=$data_output->user_type;
                $user_working_dist=$data_output->user_district;
                $user_working_tal=$data_output->user_taluka;
                $user_working_vil=$data_output->user_village;
    
    
                    if($utype=='1')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.district',$user_working_dist)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='2')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.taluka',$user_working_tal)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='3')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.village',$user_working_vil)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                } 
                
        $gramsevaks = $this->service->index();
        return view('admin.pages.gramsevak.list-gramsevaks',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocuments(Request $request)
    {
        try {
            $data_gram_doc_details = $this->service->showGramsevakDocuments($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc', compact('data_gram_doc_details'));
        } catch (\Exception $e) {
            return $e;
        }
    }


  
   

   
}