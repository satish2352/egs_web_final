<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
    Labour,
    LabourFamilyDetails,
	LabourAttendanceMark
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;



class AttendanceMarkVisibleForOfficerController extends Controller
{
   
    public function getAllAttendanceMarkedLabour(Request $request) {
        try {
            $user = Auth::user()->id;
            dd($user);
            // $date = date('Y-m-d', strtotime($request->updated_at));
            $date = date('Y-m-d'); // Get current date

        // dd($date);
            $data_output = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
            ->leftJoin('project_users', 'tbl_mark_attendance.project_id', '=', 'project_users.id')
            ->leftJoin('projects', 'project_users.project_id', '=', 'projects.id')
                ->where('tbl_mark_attendance.user_id', $user)
                ->whereDate('tbl_mark_attendance.updated_at', $date)
                  ->when($request->get('project_id'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
                })  
                // ->when($request->has('updated_at'), function($query) use ($request) {
                //     $date = date('Y-m-d', strtotime($request->updated_at));
                //     $query->whereDate('tbl_mark_attendance.updated_at', $date);
                // })
                // ->when($request->get('updated_at'), function($query) use ($request) {
                //     $query->where('tbl_mark_attendance.updated_at',$request->updated_at);
                // })  
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
