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



class LabourController extends Controller
{
    public function add(Request $request )
    {
        $all_data_validation = [
            'full_name' => 'required',
            'gender_id' => 'required',
            // 'date_of_birth' => [
            //     'required',
            //     'date_format:d/m/Y',
            //     function ($attribute, $value, $fail) {
            //         $dob = Carbon::createFromFormat('d/m/Y', $value);
            //         if ($dob->isSameDay(now()) || $dob->isAfter(now())) {
            //             $fail('The date of birth must be a date before today.');
            //         }
            //     },
            // ],
            // 'date_of_birth' => ['required', 'date_format:d/m/Y', function ($attribute, $value, $fail) {
            //     $dob = DateTime::createFromFormat('d/m/Y', $value);
            //     $eighteenYearsAgo = (new DateTime())->modify('-18 years');
            
            //     if ($dob > $eighteenYearsAgo) {
            //         $fail('The date of birth must be at least 18 years ago.');
            //     }
            // }],
            
            'district_id' => 'required',
            'taluka_id' => 'required',
            'village_id' => 'required',
            'skill_id' => 'required',
            'mobile_number' => ['required', 'digits:10'],
            'mgnrega_card_id' => ['required', 'unique:labour'],
            'latitude' => ['required', 'between:-90,90'], // Latitude range
            'longitude' => ['required', 'between:-180,180'], // Longitude range
            'aadhar_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
            'mgnrega_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048',
            'voter_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048',

            // 'family' => 'required|
            // ',
            // 'family.*.full_name' => 'required',
            // 'family.*.gender_id' => 'required',
            // 'family.*.relationship_id' => 'required',
            // 'family.*.married_status_id' => 'required',
            // 'family.*.date_of_birth' => ['required', function ($attribute, $value, $fail) {
            //     $dob = DateTime::createFromFormat('d/m/Y', $value);
            //     $currentDate = new DateTime();
            
            //     if ($dob > $currentDate) {
            //         $fail('The date of birth must be a date before today.');
            //     }
            // }],
            
            // 'family.*.date_of_birth' => [
            //     'required',
            //     'date_format:d/m/Y',
            //     function ($attribute, $value, $fail) {
            //         $dob = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
            //         if ($dob->isAfter(\Carbon\Carbon::now())) {
            //             $fail('The date of birth must be a date before today.');
            //         }
            //     },
            // ],
            

        ];


        // if(isset($request->landline_number)) {
        //     $all_data_validation['landline_number'] =  ['required', 'regex:/^[0-9]{8,}$/'];
        // }
        $validator = Validator::make($request->all(), $all_data_validation);


        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 200);
        }


        try {
            // Check if the user exists
            $user = Auth::user();
        //  dd($user);

            $labour_data = new Labour();
            $labour_data->user_id = $user->id; // Assign the user ID
            // $labour_data->user_type = $user->user_type; // Assign the user ID
            $labour_data->full_name = $request->full_name;
            $labour_data->gender_id = $request->gender_id;
            $labour_data->date_of_birth = $request->date_of_birth;//Carbon::createFromFormat('d/m/Y', )->format('Y-m-d');
            $labour_data->district_id = $request->district_id;
            $labour_data->taluka_id = $request->taluka_id;
            $labour_data->village_id = $request->village_id;
            $labour_data->mobile_number = $request->mobile_number;
            $labour_data->landline_number = $request->landline_number;
            $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
            $labour_data->skill_id = $request->skill_id;
            $labour_data->latitude = $request->latitude;
            $labour_data->longitude = $request->longitude;
            $labour_data->save();

            $last_insert_id = $labour_data->id;
            $imageAadhar = $last_insert_id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
            $imageMgnrega = $last_insert_id . '_' . rand(100000, 999999) . '_mgnrega.' . $request->mgnrega_image->extension();
            $imageProfile = $last_insert_id . '_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();
            $imageVoter = $last_insert_id . '_' . rand(100000, 999999) . '_voter.' . $request->voter_image->extension();

            $path = Config::get('DocumentConstant.USER_LABOUR_ADD');

            uploadImage($request, 'aadhar_image', $path, $imageAadhar);
            uploadImage($request, 'mgnrega_image', $path, $imageMgnrega);
            uploadImage($request, 'profile_image', $path, $imageProfile);
            uploadImage($request, 'voter_image', $path, $imageVoter);

            // Update the image paths in the database
            $labour_data->aadhar_image =  $imageAadhar;
            $labour_data->mgnrega_image = $imageMgnrega;
            $labour_data->profile_image = $imageProfile;
            $labour_data->voter_image =  $imageVoter;
            $labour_data->save();

            // Include image paths in the response
            $labour_data->aadhar_image = $labour_data->aadhar_image;
            $labour_data->mgnrega_image = $labour_data->mgnrega_image;
            $labour_data->profile_image = $labour_data->profile_image;
            $labour_data->voter_image = $labour_data->voter_image;
            // info($request->family);

            // for ($i=0; $i< sizeof($request->input('family')); $i++) {
            //     $familyDetail = new LabourFamilyDetails();
            //     $familyDetail->labour_id = $labour_data->id;
            //     $familyDetail->full_name = $request->input("family.$i.full_name");
            //     $familyDetail->gender_id = $request->input("family.$i.gender_id");
            //     $familyDetail->relationship_id = $request->input("family.$i.relationship_id");
            //     $familyDetail->married_status_id = $request->input("family.$i.married_status_id");
            //     $familyDetail->date_of_birth =  $request->input("family.$i.date_of_birth");
            //     $familyDetail->save();
            // }
            // $familyDetails = [];
            $familyDetailNew = json_decode($request->family,true);
            
            foreach ($familyDetailNew as $key => $familyMember) {
                $familyDetail = new LabourFamilyDetails();
                $familyDetail->labour_id = $labour_data->id;
                $familyDetail->full_name = $familyMember['fullName'];
                $familyDetail->gender_id = $familyMember['genderId'];
                $familyDetail->relationship_id = $familyMember['relationId'];
                $familyDetail->married_status_id = $familyMember['maritalStatusId'];
                $familyDetail->date_of_birth = $familyMember['dob'];
                $familyDetail->save();
                $familyDetails[] = $familyDetail; // Collect family details
            }
            // return response()->json(['status' => 'success', 'message' => 'Labor added successfully',  'data' => $labour_data], 200);

            return response()->json([
                'status' => 'True',
                'message' => 'Labor added successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateParticularDataLabour(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'id' => 'required|exists:labours,id',
                'aadhar_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
                'mgnrega_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048',
                'voter_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
            }

            $labour_data = Labour::findOrFail($request->id);

            $imageAadhar = $request->id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
            $imagePancard = $request->id . '_' . rand(100000, 999999) . '_pan.' . $request->mgnrega_image->extension();
            $imageProfile = $request->id . '_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();
            $imageVoter = $request->id . '_' . rand(100000, 999999) . '_voter.' . $request->voter_image->extension();

            $baseUrl = Config::get('env.FILE_VIEW');
            $path = Config::get('DocumentConstant.USER_LABOUR_ADD');

            uploadImage($request, 'aadhar_image', $path, $imageAadhar);
            uploadImage($request, 'mgnrega_image', $path, $imagePancard);
            uploadImage($request, 'profile_image', $path, $imageProfile);
            uploadImage($request, 'voter_image', $path, $imageVoter);

            // Update the image paths in the database
            $labour_data->aadhar_image = $imageAadhar;
            $labour_data->mgnrega_image = $imagePancard;
            $labour_data->profile_image = $imageProfile;
            $labour_data->voter_image = $imageVoter;
            $labour_data->save();

            // Include image paths in the response
            // $labour_data->aadhar_image = $path . '/' . $imageAadhar;
            // $labour_data->mgnrega_image = $path . '/' . $imagePancard;
            // $labour_data->profile_image = $path . '/' . $imageProfile;
            // $labour_data->voter_image = $path . '/' . $imageVoter;

            $labour_data->aadhar_image = $baseUrl . $path . '/' . $imageAadhar;
            $labour_data->mgnrega_image = $baseUrl . $path . '/' . $imagePancard;
            $labour_data->profile_image = $baseUrl . $path . '/' . $imageProfile;
            $labour_data->voter_image = $baseUrl . $path . '/' . $imageVoter;

            

            return response()->json(['status' => 'success', 'message' => 'Labor updated successfully', 'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
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
            // dd($user);
            $mgnrega_card_id = $request->input('mgnrega_card_id');
            // dd($mgnrega_card_id);
            $data_output = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('skills as skills_labour', 'labour.skill_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_reason as reason_labour', 'labour.reason_id', '=', 'reason_labour.id')
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
                    'skills_labour.skills as skills',
                    'reason_labour.reason_name as reason_name',
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
                        'gender_labour.gender_name as gender',
                        'labour_family_details.gender_id',
                        'relation_labour.relation_title as relation',
                        'labour_family_details.relationship_id',
                        'maritalstatus_labour.maritalstatus as maritalStatus',
                        'labour_family_details.married_status_id',
                        'labour_family_details.full_name',
                        'labour_family_details.date_of_birth'
                        // 'labour_family_details.id',
                        // 'gender_labour.gender_name as gender_id',
                        // 'relation_labour.relation_title as relationship_id',
                        // 'maritalstatus_labour.maritalstatus as married_status_id',
                        // 'labour_family_details.full_name',
                        // 'labour_family_details.date_of_birth'
                        
                    )
                    ->where('labour_family_details.labour_id', $labour->id)
                    ->get();
            }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Labour details get failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function getAllUserLabourList(Request $request){
        try {

            $user_id = Auth::user();

            // info('$user_id');
            // info($user_id);

            $data_output = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
               
                ->when($request->get('project_id'), function($query) use ($request) {
                    
                    $query->leftJoin('tbl_mark_attendance', 'labour.mgnrega_card_id', '=', 'tbl_mark_attendance.mgnrega_card_id');
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
                })
                ->where('labour.user_id',$user_id->id)
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
                // $labour->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->aadhar_image;
                // $labour->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->mgnrega_image;
                // $labour->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->voter_image;

               
            }
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

            return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function filterLabourList(Request $request){
        try {
            $query = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->when($request->get('user_id'), function($query) use ($request) {
                    $query->where('labour.user_id',$request->user_id);
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
                );

            // Apply filters if provided
            if ($request->has('district_id')) {
                $query->where('district_labour.location_id', $request->input('district_id'));
            }
            if ($request->has('taluka_id')) {
                $query->where('taluka_labour.location_id', $request->input('taluka_id'));
            }
            if ($request->has('village_id')) {
                $query->where('village_labour.location_id', $request->input('village_id'));
            }

            $data_output = $query->get();

            return response()->json(['status' => 'success', 'message' => 'Filtered data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function getLabourStatusList(Request $request, $is_approved){
        try {
            $user = Auth::user()->id;
            $data_output = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->where('registrationstatus.is_active', true)
                ->where('labour.is_approved', $is_approved)
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
                    'registrationstatus.status_name'
                )->get();
    
            foreach ($data_output as $labour) {
                // Append image paths to the output data
                $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                
            }
    
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Failed to retrieve labour list','error' => $e->getMessage()], 500);
        }
    }
    public function getSendApprovedLabourList(Request $request) {
        return $this->getLabourStatusList($request, 1);
    }
    public function getApprovedLabourList(Request $request) {
        return $this->getLabourStatusList($request, 2);
    }
    public function getNotApprovedLabourList(Request $request) {
        return $this->getLabourStatusList($request, 3);
    }
    public function getRejectedLabourList(Request $request) {
        return $this->getLabourStatusList($request, 4);
    }
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
                // 'other_remark' => 'required',
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
                $history->roles_id = $user->role_id; 
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
    
    public function getParticularLabourForUpdate(Request $request){
        try {
            $user = Auth::user()->id;
            $mgnrega_card_id = $request->input('mgnrega_card_id');
            $data_output = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('skills as skills_labour', 'labour.skill_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_reason as reason_labour', 'labour.reason_id', '=', 'reason_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->where('labour.mgnrega_card_id', $mgnrega_card_id)
                ->select(
                    'labour.id',
                    'labour.full_name',
                    'labour.date_of_birth',
                    'labour.gender_id',
                    'gender_labour.gender_name as gender_name',
                    'labour.skill_id',
                    'skills_labour.skills as skills',
                    'labour.reason_id',
                    'reason_labour.reason_name as reason_name',
                    'labour.district_id',
                    'district_labour.name as district_name',
                    'labour.taluka_id',
                    'taluka_labour.name as taluka_name',
                    'labour.village_id',
                    'village_labour.name as village_name',
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
                        'labour_family_details.gender_id',
                        'gender_labour.gender_name as gender',
                        'labour_family_details.relationship_id',
                        'relation_labour.relation_title as relation',
                        'labour_family_details.married_status_id',
                        'maritalstatus_labour.maritalstatus as maritalStatus',
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

//     public function updateLabourFirstForm(Request $request){
//     try {
//         $user = Auth::user();
//         $validator = Validator::make($request->all(), [
//             'full_name' => 'required',
//             'gender_id' => 'required',
//             'district_id' => 'required',
//             'taluka_id' => 'required',
//             'village_id' => 'required',
//             'skill_id' => 'required',
//             'mobile_number' => ['required', 'digits:10'],
//             'mgnrega_card_id' => ['required'],
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
//         }

//         // Find the labour data to update
//         $labour_data = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)->first();

//         if (!$labour_data) {
//             return response()->json(['status' => 'error', 'message' => 'Labour data not found'], 200);
//         }

//         // Update labour details
//         $labour_data->user_id = $user->id;
//         $labour_data->full_name = $request->full_name;
//         $labour_data->gender_id = $request->gender_id;
//         $labour_data->date_of_birth = $request->date_of_birth;
//         $labour_data->skill_id = $request->skill_id;  
//         $labour_data->district_id = $request->district_id;
//         $labour_data->taluka_id = $request->taluka_id;
//         $labour_data->village_id = $request->village_id;
//         $labour_data->mobile_number = $request->mobile_number;
//         $labour_data->landline_number = $request->landline_number;
//         $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
           
//         $labour_data->save();

//         return response()->json(['status' => 'true', 'message' => 'Labour updated successfully', 'data' => $labour_data], 200);
//     } catch (\Exception $e) {
//         return response()->json(['status' => 'false', 'message' => 'Labour update failed', 'error' => $e->getMessage()], 500);
//     }
//    }

public function updateLabourFirstForm(Request $request){
    try {
        $user = Auth::user();
        // $labour_id = $request->input('id');
        // dd($mgnrega_card_id);
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'gender_id' => 'required',
            'district_id' => 'required',
            'taluka_id' => 'required',
            'village_id' => 'required',
            'skill_id' => 'required',
            'mobile_number' => ['required', 'digits:10'],
            // 'mgnrega_card_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }

        // Find the labour data to update
        $labour_data = Labour::where('id', $request->id)->first();
// dd($labour_data);
        if (!$labour_data) {
            return response()->json(['status' => 'error', 'message' => 'Labour data not found'], 200);
        }

         // Check if the mgnrega_card_id can be updated based on is_approved
        if ($labour_data->is_approved == 2) {
            return response()->json(['status' => 'error', 'message' => 'Cannot update mgnrega card id when labour is approved'], 200);
        }

        // Check if the provided mgnrega_card_id already exists in the database
        $existingLabour = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)->first();
        // if ($existingLabour && $existingLabour->id !== $labour_data->id ) {
        //     return response()->json(['status' => 'error', 'message' => 'MGNREGA card ID already exists'], 200);
        // }

        if ($existingLabour) {
            if ($existingLabour->is_approved == 2) {
                // If is_approved is 2, do not update the MGNREGA card ID
                return response()->json(['status' => 'error', 'message' => 'MGNREGA card ID already exists and is not approved for update'], 200);
            } else {
                // If is_approved is 1 or 3, update the MGNREGA card ID
                if ($existingLabour->id !== $labour_data->id) {
                    return response()->json(['status' => 'error', 'message' => 'MGNREGA card ID already exists'], 200);
                }
            }
        }
        // Update labour details
        $labour_data->user_id = $user->id;
        $labour_data->full_name = $request->full_name;
        $labour_data->gender_id = $request->gender_id;
        $labour_data->date_of_birth = $request->date_of_birth;
        $labour_data->skill_id = $request->skill_id;  
        $labour_data->district_id = $request->district_id;
        $labour_data->taluka_id = $request->taluka_id;
        $labour_data->village_id = $request->village_id;
        $labour_data->mobile_number = $request->mobile_number;
        $labour_data->landline_number = $request->landline_number;
        // $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
        if ($labour_data->is_approved != 2) {
            $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
        }
        $labour_data->save();

        return response()->json(['status' => 'true', 'message' => 'Labour updated successfully', 'data' => $labour_data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'false', 'message' => 'Labour update failed', 'error' => $e->getMessage()], 500);
    }
   }

public function updateLabourSecondForm(Request $request)
{
    try {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'latitude' => ['required', 'between:-90,90'], // Latitude range
            'longitude' => ['required', 'between:-180,180'], // Longitude range
            'aadhar_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
            'mgnrega_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048',
            'voter_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }

        // Find the labour data to update
        $labour_data = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)->first();

        if (!$labour_data) {
            return response()->json(['status' => 'error', 'message' => 'Labour data not found'], 200);
        }

        // Delete existing family details for the labor
        // LabourFamilyDetails::where('labour_id', $labour_data->id)->delete();

         // Check if labour_id is greater than zero before deleting family details
         if ($labour_data->id > 0) {
            LabourFamilyDetails::where('labour_id', $labour_data->id)->delete();
        }

        $labour_data->user_id = $user->id;
        $labour_data->latitude = $request->latitude;
        $labour_data->longitude = $request->longitude;
        $labour_data->is_approved = 1;

        $labour_data->save();

        $pathdelete = Config::get('DocumentConstant.USER_LABOUR_DELETE');
        $path = Config::get('DocumentConstant.USER_LABOUR_ADD');
        $baseUrl = Config::get('env.FILE_VIEW');

        // Upload and update images
        if ($request->hasFile('profile_image')) {
        if ($labour_data->profile_image) {
            removeImage($pathdelete . $labour_data->profile_image);
        }
        $profileImageName = $labour_data->id . '_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();
        uploadImage($request, 'profile_image', $path, $profileImageName);
        $labour_data->profile_image = $profileImageName;
        }

        if ($request->hasFile('aadhar_image')) {
        if ($labour_data->aadhar_image) {
            removeImage($pathdelete . $labour_data->aadhar_image);
        }
        $aadharImageName = $labour_data->id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
        uploadImage($request, 'aadhar_image', $path, $aadharImageName);
        $labour_data->aadhar_image = $aadharImageName;
        }

        if ($request->hasFile('mgnrega_image')) {
        if ($labour_data->mgnrega_image) {
            removeImage($pathdelete . $labour_data->mgnrega_image);
        }
        $mgnregaImageName = $labour_data->id . '_' . rand(100000, 999999) . '_mgnrega.' . $request->mgnrega_image->extension();
        uploadImage($request, 'mgnrega_image', $path, $mgnregaImageName);
        $labour_data->mgnrega_image = $mgnregaImageName;
        }

        if ($request->hasFile('voter_image')) {
        if ($labour_data->voter_image) {
            removeImage($pathdelete . $labour_data->voter_image);
        }
        $voterImageName = $labour_data->id . '_' . rand(100000, 999999) . '_voter.' . $request->voter_image->extension();
        uploadImage($request, 'voter_image', $path, $voterImageName);
        $labour_data->voter_image = $voterImageName;
        }
        $labour_data->is_resubmitted = true;
        $labour_data->save();

        // $familyDetails = [];


        $familyDetailNew = json_decode($request->family,true);
            
        if ($labour_data->id > 0) {
        foreach ($familyDetailNew as $key => $familyMember) {
            $familyDetail = new LabourFamilyDetails();
            $familyDetail->labour_id = $labour_data->id;
            $familyDetail->full_name = $familyMember['full_name'];
            $familyDetail->gender_id = $familyMember['gender_id'];
            // $familyDetail->gender_id = $familyMember['gender'];
            $familyDetail->relationship_id = $familyMember['relationship_id'];
            // $familyDetail->relationship_id = $familyMember['relation'];
            $familyDetail->married_status_id = $familyMember['married_status_id'];
            // $familyDetail->married_status_id = $familyMember['maritalStatus'];
            $familyDetail->date_of_birth = $familyMember['date_of_birth'];           
            $familyDetail->save();
            $familyDetails[] = $familyDetail; // Collect family details
        }
    }

        return response()->json(['status' => 'true', 'message' => 'Labour updated successfully', 'data' => $labour_data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'false', 'message' => 'Labour update failed', 'error' => $e->getMessage()], 500);
    }
}


    
}
