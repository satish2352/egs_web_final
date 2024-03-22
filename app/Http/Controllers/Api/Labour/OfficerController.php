<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Validator;
use App\Models\ {
	Labour,
    User,
    LabourFamilyDetails,
    HistoryModel
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;



class OfficerController extends Controller
{
   

    public function getAllLabourList(Request $request){
    
        try {
            $user = Auth::user()->id;
            $data_output = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->where('labour.is_approved', 2)
                ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
                    $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
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
                    'labour.voter_image',

                )->get();

                foreach ($data_output as $labour) {
                    // Append image paths to the output data
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                    $labour->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->aadhar_image;
                    $labour->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->mgnrega_image;
                    $labour->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->voter_image;

                    // Check if family details exist before iterating over them
                    if (!is_null($labour->family_details)) {
                        foreach ($labour->family_details as $familyDetail) {
                            $familyDetail->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $familyDetail->profile_image;
                            $familyDetail->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $familyDetail->aadhar_image;
                            $familyDetail->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $familyDetail->mgnrega_image;
                            $familyDetail->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $familyDetail->voter_image;
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
    public function getParticularLabour(Request $request){
        try {
            $user = Auth::user()->id;
            $mgnrega_card_id = $request->input('mgnrega_card_id');
            $data_output = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->where('labour.mgnrega_card_id', $mgnrega_card_id)
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
                    'labour.voter_image',
                    'labour.other_remark',
                    'registrationstatus.status_name'


                )->get();

                foreach ($data_output as $labour) {
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                    $labour->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->aadhar_image;
                    $labour->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->mgnrega_image;
                    $labour->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->voter_image;
                  }

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
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Labour details get failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function getLabourStatusListReceived(Request $request, $is_approved){
        try {
            $user = Auth::user()->id;
            // dd($user);

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

            // if($utype=='1')
            // {
                $data_labour = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('skills as skills_labour', 'labour.gender_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->whereIn('labour.user_id',$data_user_output)
                ->where('registrationstatus.is_active', true)
                ->select(
                    'labour.id',
                    'labour.full_name',
                    'labour.date_of_birth',
                    'gender_labour.gender_name as gender_name',
                    'skills_labour.skills as skills',
                    'district_labour.name as district_id',
                    'taluka_labour.name as taluka_id',
                    'village_labour.name as village_id',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'registrationstatus.status_name',
                )
                    ->get()
					->toArray();
                    dd($data_labour);
                    die();
         

            foreach ($data_output as $labour) {
                // Append image paths to the output data
                $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                
            }
    
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Failed to retrieve labour list','error' => $e->getMessage()], 500);
        }
    }
    public function getSendApprovedLabourListOfficer(Request $request) {
        return $this->getLabourStatusListReceived($request, 1);
    }
    public function getApprovedLabourList(Request $request) {
        return $this->getLabourStatusList($request, 2);
    }
    
    public function getNotApprovedLabourList(Request $request) {
        return $this->getLabourStatusList($request, 3);
    }

    // public function getOtherLabourList(Request $request) {
    //     return $this->getLabourStatusList($request, 4);
    // }

    public function updateLabourStatusApproved(Request $request){
    
        try {
            $user = Auth::user()->id;
    
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'mgnrega_card_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
            }
            
            $updated = Labour::where('user_id', $user)
                ->where('mgnrega_card_id', $request->mgnrega_card_id)
                ->where('is_approved', 1)
                ->update(['is_approved' => 2]); 
                
    
            if ($updated) {
                return response()->json(['status' => 'true', 'message' => 'Labour status updated successfully'], 200);
            } else {
                return response()->json(['status' => 'false', 'message' => 'No labour found with the provided MGNREGA card Id'], 200);
            }
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed','error' => $e->getMessage()], 500);
        }
    }
    public function updateLabourStatusNotApproved(Request $request) {
        try {
            $user = Auth::user();
    
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'mgnrega_card_id' => 'required',
                'reason_id' => 'required',
                'other_remark' => 'required',
                'is_approved' => 'required',
                
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
            }
    
            // Update labor entry
            $updated = Labour::where('user_id', $user->id)
                ->where('mgnrega_card_id', $request->mgnrega_card_id)
                ->where('is_approved', 1)
                ->update([
                    'is_approved' => 3,
                    'reason_id' => $request->reason_id, 
                    'other_remark' => $request->other_remark, 
                ]);
    
            if ($updated) {
                // Create a history record
                $history = new HistoryModel();
                $history->user_id = $user->id; 
                $history->role_id = $user->role_id; 
                $history->mgnrega_card_id = $request->mgnrega_card_id;
                $history->is_approved = $request->is_approved;
                $history->reason_id = $request->reason_id; 
                $history->other_remark = $request->other_remark; 
                
    
                $history->save();
    
                return response()->json(['status' => 'true', 'message' => 'Labour status updated successfully'], 200);
            } else {
                return response()->json(['status' => 'false', 'message' => 'No labor found with the provided MGNREGA card Id or status is not approved'], 200);
            }
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }
    
    
    


    
}
