<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
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
            'project_id' => 'required|numeric',
            'mgnrega_card_id' => 'required|numeric',
            'attendance_day' => 'required', // Assuming 'attendance_day' should be a date
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }
    
        try {
            // Check if the user exists
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }
            
            $labour_data = new LabourAttendanceMark();
            $labour_data->user_id = $user->id; // Assign the user ID
            $labour_data->project_id = $request->project_id;
            $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
            $labour_data->attendance_day = $request->attendance_day;
          
            $labour_data->save();
    
            return response()->json(['status' => 'success', 'message' => 'Attendance Mark successfully added', 'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
public function getAllAttendancMarkedLabour(Request $request){
    try {
        $data_output = LabourAttendanceMark::leftJoin('labour', 'labour.gender_id', '=', 'gender_labour.id')

            ->when($request->get('mgnrega_card_id'), function($query) use ($request) {
                $query->where('labour.mgnrega_card_id',$request->mgnrega_card_id);
            })
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
            )->get();

            foreach ($data_output as $labour) {
                // Append image paths to the output data
                $labour->profile_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $labour->profile_image;
                $labour->aadhar_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $labour->aadhar_image;
                $labour->mgnrega_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $labour->mgnrega_image;
                $labour->voter_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $labour->voter_image;

                // Check if family details exist before iterating over them
                if (!is_null($labour->family_details)) {
                    foreach ($labour->family_details as $familyDetail) {
                        $familyDetail->profile_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $familyDetail->profile_image;
                        $familyDetail->aadhar_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $familyDetail->aadhar_image;
                        $familyDetail->mgnrega_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $familyDetail->mgnrega_image;
                        $familyDetail->voter_image = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $familyDetail->voter_image;
                    }
                }
            }

        // Loop through labour data and retrieve family details for each labour
        foreach ($data_output as $labour) {
            $labour->family_details = LabourFamilyDetails::leftJoin('gender as gender_labour', 'labour_family_details.gender_id', '=', 'gender_labour.id')
            ->leftJoin('relation as relation_labour', 'labour_family_details.relationship_id', '=', 'relation_labour.id')
            ->leftJoin('maritalstatus as maritalstatus_labour', 'labour_family_details.married_status_id', '=', 'maritalstatus_labour.id')
                ->select(
                    'labour_family_details.id',
                    'gender_labour.gender_name as gender_id',
                    'relation_labour.relation_title as relationship_id',
                    'maritalstatus_labour.maritalstatus as married_status_id',
                    'labour_family_details.full_name',
                    'labour_family_details.date_of_birth'
                )
                ->where('labour_family_details.labour_id', $labour->id)
                ->get();
        }
        return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}
