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
        // $labour_data = null; 

        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'mgnrega_card_id' => 'required',
            'attendance_day' => 'required', 
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

            // Check if labour status is approved
            $labour = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)->first();
            if (!$labour || $labour->is_approved != 2) {
                return response()->json(['status' => 'error', 'message' => 'Labour status not approved'], 200);
            }
            

            $fromDate = date('Y-m-d').' 00:00:01';
            $toDate =  date('Y-m-d').' 23:59:59';

            $existingEntry = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                            ->whereBetween('updated_at', [$fromDate, $toDate])
                                            ->first();

            if ($existingEntry  && $existingEntry->attendance_day =='full_day') {
                return response()->json(['status' => 'error', 'message' => 'Attendance for this card ID already marked for today'], 200);
            } else {
            
                $fromDate = date('Y-m-d').' 05:00:01';
                $toDate =  date('Y-m-d').' 13:00:00';

                $firstHalfWorkAttendance = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                                ->whereBetween('updated_at', [$fromDate, $toDate])
                                                ->first();

                //Second half day entry
                $secondHalfWorkAttendance = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                                ->where('updated_at', '>',  date('Y-m-d').' 13:00:00')
                                                ->get()->toArray();

                if($firstHalfWorkAttendance) {
                    if(date('Y-m-d H:i:s') >  date('Y-m-d').' 13:00:00') {
                        if($request->attendance_day =='half_day' && (count($secondHalfWorkAttendance)<=0) ) {

                            if($firstHalfWorkAttendance->project_id == $request->project_id) {
                                $labour_data = LabourAttendanceMark::where(['id'=> $firstHalfWorkAttendance->id,
                                'mgnrega_card_id'=> $firstHalfWorkAttendance->mgnrega_card_id])->update(['attendance_day'=>'full_day']);
                             
                            } else  {

                                $labour_data = new LabourAttendanceMark();
                                $labour_data->user_id = $user->id; // Assign the user ID
                                $labour_data->project_id = $request->project_id;
                                $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
                                $labour_data->attendance_day = $request->attendance_day;
                            
                                $labour_data->save();
                       
                            }
                           
                    
                        }  else {
                            return response()->json(['status' => 'error', 'message' => 'Attendance cant be mark as half/full day because halday alreay present for today'], 200);
                        }
                    }

                } elseif((count($secondHalfWorkAttendance)>=1) && ($secondHalfWorkAttendance[0]['project_id'] == $request->project_id) ) {
                    return response()->json(['status' => 'error', 'message' => 'Attendance cant be mark as half/full day because halday alreay present for today'], 200);
                
                }else {
                    $labour_data = new LabourAttendanceMark();
                    $labour_data->user_id = $user->id; // Assign the user ID
                    $labour_data->project_id = $request->project_id;
                    $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
                    $labour_data->attendance_day = $request->attendance_day;
                    $labour_data->save();
                    
                }

            }
            return response()->json(['status' => 'true', 'message' => 'Attendance Mark successfully added', 'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance Mark Fail','error' => $e->getMessage()], 500);
        }
    }
    
    // public function getAllAttendanceMarkedLabour(Request $request) {
    //     try {
    //         $user = Auth::user()->id;
          
    //         $date = date('Y-m-d');     
    //         $data_output = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
    //             ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
    //             ->where('tbl_mark_attendance.user_id', $user)
    //             ->whereDate('tbl_mark_attendance.updated_at', $date)
    //               ->when($request->get('project_id'), function($query) use ($request) {
    //                 $query->where('tbl_mark_attendance.project_id',$request->project_id);
    //             })  
    //             ->select(
    //                 'tbl_mark_attendance.id',
    //                 'tbl_mark_attendance.project_id',
    //                 'projects.project_name',
    //                 'labour.full_name as full_name',
    //                 'labour.date_of_birth',
    //                 'labour.mobile_number',
    //                 'labour.landline_number',
    //                 'labour.mgnrega_card_id',
    //                 'labour.latitude',
    //                 'labour.longitude',
    //                 'labour.profile_image',
    //                 'tbl_mark_attendance.attendance_day',
    //                 'tbl_mark_attendance.updated_at'

    //             )->get();
    
    //             foreach ($data_output as $labour) {
                   
    //                 $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
    //             }
    
    //         return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
    //     }
    // }

    public function getAllAttendanceMarkedLabour(Request $request) {
        try {
            $user = Auth::user()->id;

        
            $pageNo = $request->has('pageNo');
            $pageSize = $request->has('pageSize');

        //  dd( $pageSize);
            $date = date('Y-m-d');     
            $data_output = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
                ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                ->where('tbl_mark_attendance.user_id', $user)
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

                )
                ->paginate($pageSize, ['*'], 'pageNo', $pageNo);
                // ->get();
    
                foreach ($data_output as $labour) {
                   
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                }
    
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
        }
    }

    // public function updateAttendanceMark(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'project_id' => 'required',
    //             'labour_id' => 'required',
    //             'attendance_day' => 'required',
            
    //         ]);
    //         // Check if validation fails
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
    //         }
    //         // Find the attendance mark data
    //         $attendance_mark_data = LabourAttendanceMark::where('id', $request->labour_id)
    //             ->first();

    //         // Check if attendance mark data exists
    //         if (!$attendance_mark_data) {
    //             return response()->json(['status' => 'error', 'message' => 'Attendance mark data not found'], 200);
    //         }
    //         $attendance_mark_data->project_id = $request->project_id;
    //         $attendance_mark_data->mgnrega_card_id = $request->mgnrega_card_id;
    //         $attendance_mark_data->attendance_day = $request->attendance_day;

    //         // Save the updated record
    //         $attendance_mark_data->save();

    //         return response()->json(['status' => 'true', 'message' => 'Attendance mark updated successfully', 'data' => $attendance_mark_data], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Attendance mark update failed', 'error' => $e->getMessage()], 500);
    //     }
    // }

 
    public function updateAttendanceMark(Request $request)
{
    try {
        $user = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'mgnrega_card_id' => 'required',
            'attendance_day' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }
        $fromDate = date('Y-m-d').' 00:00:01';
        $toDate =  date('Y-m-d').' 23:59:59';

        $existingEntry = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                        ->whereBetween('updated_at', [$fromDate, $toDate])
                                        ->first();
        
        if ($existingEntry) {
            if (date('H:i:s') < '13:00:00') {
                if($existingEntry->project_id == $request->project_id){
                    $existingEntry->attendance_day = 'half_day';
                    $existingEntry->save();
                }
                else{
                    $existingEntry->project_id = $request->project_id;
                    $existingEntry->mgnrega_card_id = $request->mgnrega_card_id;
                    $existingEntry->attendance_day = $request->attendance_day;
                } 


            }
            elseif(date('H:i:s') > '13:00:00'){
                dd('hiii');
            if($existingEntry && $existingEntry->attendance_day == 'full_day' && $existingEntry->project_id == $request->project_id){
                $existingEntry->attendance_day = 'half_day';
                $existingEntry->save();
            }
            elseif($existingEntry && $existingEntry->attendance_day == 'full_day'){

                $existingEntry->attendance_day = 'half_day';
                $existingEntry->save();

                $newEntry = new LabourAttendanceMark();
                
                $newEntry->user_id = $user;
                $newEntry->project_id = $request->project_id;
                $newEntry->mgnrega_card_id = $request->mgnrega_card_id;
                $newEntry->attendance_day = 'half_day'; 
                $newEntry->save();
            }

        }
        }
        else {
            return response()->json(['status' => 'success', 'message' => 'Attendance not found', 'data' => $existingEntry], 200);
        }

        return response()->json(['status' => 'success', 'message' => 'Attendance mark updated successfully', 'data' => $existingEntry], 200);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Attendance mark update failed', 'error' => $e->getMessage()], 500);
    }
}

    




}
