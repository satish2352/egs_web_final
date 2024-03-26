<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
    Labour,
    Project,
    LabourFamilyDetails,
	LabourAttendanceMark
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;



class AttendanceMarkVisibleForOfficerController extends Controller
{
    public function getAllProjectListForOfficer(Request $request) {
        try {
            $user = Auth::user()->id;            
            $date = date('Y-m-d'); 
            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
            ->where('users.id', $user)
            ->first();

        $utype=$data_output->user_type;
        $user_working_dist=$data_output->user_district;
        $user_working_tal=$data_output->user_taluka;
        $user_working_vil=$data_output->user_village;

        // dd($user_working_dist);
        if($utype=='1')
        {
        $data_user_output = User::where('users.user_district', $user_working_dist)
        ->select('id')
            ->get()
            ->toArray();
        }else if($utype=='2')
        {
            $data_user_output = User::where('users.user_taluka', $user_working_tal)
            ->select('id')
            ->get()
            ->toArray();
        }else if($utype=='3')
        {
            $data_user_output = User::where('users.user_village', $user_working_vil)
            ->select('id')
            ->get()
            ->toArray();
        }         
        // dd($data_user_output);
        // dd($user_working_dist);
            $data_output = Project::leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
            ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
            ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
            ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')  
            ->where('projects.District', $user_working_dist)
                // ->whereDate('tbl_mark_attendance.updated_at', $date)
                //   ->when($request->get('project_id'), function($query) use ($request) {
                //     $query->where('tbl_mark_attendance.project_id',$request->project_id);
                // }
                // )  
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
                )->get();
                // dd($data_output);
                    return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
        }
    }

   
    public function getAllAttendanceMarkedLabour(Request $request) {
        try {
            $user = Auth::user()->id;            
            $date = date('Y-m-d'); 

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
            ->where('users.id', $user)
            ->first();

        $utype=$data_output->user_type;
        $user_working_dist=$data_output->user_district;
        $user_working_tal=$data_output->user_taluka;
        $user_working_vil=$data_output->user_village;

        if($utype=='1')
        {
        $data_user_output = User::where('users.user_district', $user_working_dist)
        ->select('id')
            ->get()
            ->toArray();
        }else if($utype=='2')
        {
            $data_user_output = User::where('users.user_taluka', $user_working_tal)
            ->select('id')
            ->get()
            ->toArray();
        }else if($utype=='3')
        {
            $data_user_output = User::where('users.user_village', $user_working_vil)
            ->select('id')
            ->get()
            ->toArray();
        }         
        // dd($data_user_output);
        // dd($user_working_dist);
            $data_output = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
            ->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
            ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
            ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
            ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('projects.District', $user_working_dist)
                ->whereDate('tbl_mark_attendance.updated_at', $date)
                ->when($request->get('project_id'), function($query) use ($request) {
                    $query->leftJoin('tbl_mark_attendance as ma1', 'labour.mgnrega_card_id', '=', 'ma1.mgnrega_card_id');
                    $query->where('ma1.project_id', $request->project_id);
                })
                ->when($request->get('taluka_id'), function($query) use ($request) {
                    $query->where('labour.taluka_id', $request->taluka_id);
                })  
                ->when($request->get('village_id'), function($query) use ($request) {
                    $query->where('labour.village_id', $request->village_id);
                })
                
                // ->when($request->get('project_id'), function($query) use ($request) {
                //     $query->leftJoin('tbl_mark_attendance', 'labour.mgnrega_card_id', '=', 'tbl_mark_attendance.mgnrega_card_id');
                //     $query->leftJoin('tbl_mark_attendance', 'labour.mgnrega_card_id', '=', 'tbl_mark_attendance.mgnrega_card_id');
                //     $query->where('tbl_mark_attendance.project_id',$request->project_id);
                // })

                //   ->when($request->get('taluka_id'), function($query) use ($request) {
                //     $query->where('labour.taluka_id',$request->taluka_id);
                // })  
                // ->when($request->get('village_id'), function($query) use ($request) {
                //     $query->where('labour.village_id',$request->village_id);
                // })  
                ->select(
                    'tbl_mark_attendance.id',
                    'users.f_name',
                    'tbl_mark_attendance.project_id',
                    'projects.project_name',
                    'labour.full_name as full_name',
                    'labour.date_of_birth',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.taluka_id',
                    'taluka_labour.name as taluka_name',
                    'labour.village_id',
                    'village_labour.name as village_name',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'tbl_mark_attendance.attendance_day',
                    'tbl_mark_attendance.updated_at'

                )->get();
    
                foreach ($data_output as $labour) {
                    // Append image paths to the output data
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                                    
                }
                return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
        }
    }
    
}
