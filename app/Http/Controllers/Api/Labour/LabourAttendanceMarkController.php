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



class LabourAttendanceMarkController extends Controller
{
    public function addAttendanceMark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'mgnrega_card_id' => 'required',
            'attendance_day' => 'required', // Assuming 'attendance_day' should be a date
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 200);
        }
    
        try {
            // Check if the user exists
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 200);
            }

             
        // $existingEntry = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)->first();
        // if ($existingEntry) {
        //     return response()->json(['status' => 'error', 'message' => 'mgnrega card id already exists'], 400);
        // }

         
         $existingEntry = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
        //  ->whereDate('attendance_day', '=', date('Y-m-d'))
        ->whereDate('updated_at', '=', now()->toDateString())
         ->first();

     if ($existingEntry) {
         return response()->json(['status' => 'error', 'message' => 'Attendance for this card ID already marked for today'], 200);
     }
            
            $labour_data = new LabourAttendanceMark();
            $labour_data->user_id = $user->id; // Assign the user ID
            $labour_data->project_id = $request->project_id;
            $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
            $labour_data->attendance_day = $request->attendance_day;
          
            $labour_data->save();
    
            return response()->json(['status' => 'true', 'message' => 'Attendance Mark successfully added', 'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance Mark Fail','error' => $e->getMessage()], 500);
        }
    }
    
    public function getAllAttendanceMarkedLabour(Request $request) {
        try {
            $user = Auth::user()->id;
            $data_output = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
            ->leftJoin('project_users', 'tbl_mark_attendance.project_id', '=', 'project_users.id')
            ->leftJoin('projects', 'project_users.project_id', '=', 'projects.id')
                ->where('tbl_mark_attendance.user_id', $user)
                  ->when($request->get('project_id'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
                })  
                ->when($request->has('updated_at'), function($query) use ($request) {
                    $date = date('Y-m-d', strtotime($request->updated_at));
                    $query->whereDate('tbl_mark_attendance.updated_at', $date);
                })
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
    

    public function updateAttendanceMark(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'mgnrega_card_id' => 'required',
            'attendance_day' => 'required',
           
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }
        // Find the attendance mark data
        $attendance_mark_data = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
            ->first();

        // Check if attendance mark data exists
        if (!$attendance_mark_data) {
            return response()->json(['status' => 'error', 'message' => 'Attendance mark data not found'], 200);
        }
        $attendance_mark_data->project_id = $request->project_id;
        $attendance_mark_data->mgnrega_card_id = $request->mgnrega_card_id;
        $attendance_mark_data->attendance_day = $request->attendance_day;

        // Save the updated record
        $attendance_mark_data->save();

        // Respond with success message and updated data
        return response()->json(['status' => 'success', 'message' => 'Attendance mark updated successfully', 'data' => $attendance_mark_data], 200);
    } catch (\Exception $e) {
        // Respond with error message if an exception occurs
        return response()->json(['status' => 'error', 'message' => 'Attendance mark update failed', 'error' => $e->getMessage()], 500);
    }
}

}
