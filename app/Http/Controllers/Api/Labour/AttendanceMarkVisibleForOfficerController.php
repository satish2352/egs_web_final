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
            $date = date('Y-m-d'); // Get current date

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
            $data_output = Project::leftJoin('tbl_mark_attendance', 'projects.id', '=', 'tbl_mark_attendance.project_id')
            ->leftJoin('tbl_area as state_projects', 'projects.state', '=', 'state_projects.location_id')
            ->leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
            ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
            ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')  
            ->where('tbl_mark_attendance.user_id', $data_user_output)
                ->whereDate('tbl_mark_attendance.updated_at', $date)
                  ->when($request->get('project_id'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
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
                )->get();
    
                // foreach ($data_output as $labour) {
                //     // Append image paths to the output data
                //     $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                                    
                // }
    
            
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
        }
    }

   
    public function getAllAttendanceMarkedLabour(Request $request) {
        try {
            $user = Auth::user()->id;            
            $date = date('Y-m-d'); // Get current date

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
            // ->leftJoin('project_users', 'tbl_mark_attendance.project_id', '=', 'project_users.id')
            ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                ->where('tbl_mark_attendance.user_id', $data_user_output)
                ->whereDate('tbl_mark_attendance.updated_at', $date)
                  ->when($request->get('project_id'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
                })  
                ->select(
                    'tbl_mark_attendance.id',
                    'tbl_mark_attendance.project_id',
                    'projects.project_name',
                    'labour.full_name as full_name',
                    'labour.date_of_birth',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
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
    
            // Loop through labour data and retrieve family details for each labour
            // foreach ($data_output as $labour) {
            //     $labour->family_details = LabourFamilyDetails::leftJoin('gender as gender_labour', 'labour_family_details.gender_id', '=', 'gender_labour.id')
            //     ->leftJoin('relation as relation_labour', 'labour_family_details.relationship_id', '=', 'relation_labour.id')
            //     ->leftJoin('maritalstatus as maritalstatus_labour', 'labour_family_details.married_status_id', '=', 'maritalstatus_labour.id')
            //         ->select(
            //             'labour_family_details.id',
            //             'gender_labour.gender_name as gender_id',
            //             'relation_labour.relation_title as relationship_id',
            //             'maritalstatus_labour.maritalstatus as married_status_id',
            //             'labour_family_details.full_name',
            //             'labour_family_details.date_of_birth'
            //         )
            //         ->where('labour_family_details.labour_id', $labour->id)
            //         ->get();
            // }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
        }
    }
    

    

}
