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
    LabourFamilyDetails
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

            $labour_data = new Labour();
            $labour_data->user_id = $user->id; // Assign the user ID
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
            // $labour_data->aadhar_image = 'null';
            // $labour_data->mgnrega_image = 'null';
            // $labour_data->profile_image = 'null';
            // $labour_data->voter_image = 'null';
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
            $data_output = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
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
            $data_output = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
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

    // public function filtermgnregaIdLabourList(Request $request){
    //     try {
    //         $query = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
    //             ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
    //             ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
    //             ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
    //             ->select(
    //                 'labour.id',
    //                 'labour.full_name',
    //                 'labour.date_of_birth',
    //                 'gender_labour.gender_name as gender_name',
    //                 'district_labour.name as district_id',
    //                 'taluka_labour.name as taluka_id',
    //                 'village_labour.name as village_id',
    //                 'labour.mobile_number',
    //                 'labour.landline_number',
    //                 'labour.mgnrega_card_id',
    //                 'labour.latitude',
    //                 'labour.longitude',
    //                 'labour.profile_image',
    //                 'labour.aadhar_image',
    //                 'labour.mgnrega_image',
    //                 'labour.profile_image',
    //             );

    //         // Apply filters if provided
    //         // if ($request->has('mgnrega_card_id')) {
    //         //     $query->where('labour.mgnrega_card_id', 'like', '%' . $request->input('mgnrega_card_id') . '%');
    //         // }
        
    //         $data_output = $query->get();

    //         return response()->json(['status' => 'success', 'message' => 'Filtered data retrieved successfully', 'data' => $data_output], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    //     }
    // }
    
    public function getSendApprovedLabourList(Request $request){
    
        try {
            $user = Auth::user()->id;
            $data_output = Labour::leftJoin('registrationstatus', 'labour.status', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->where('registrationstatus.is_active', true)
                ->where('labour.status', 1)
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
                    'registrationstatus.status_name'
                    

                )->get();

                foreach ($data_output as $labour) {
                    // Append image paths to the output data
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                    $labour->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->aadhar_image;
                    $labour->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->mgnrega_image;
                    $labour->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->voter_image;
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
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Send Approved Labour List fail','error' => $e->getMessage()], 500);
        }
    }

    public function getApprovedLabourList(Request $request){
    
        try {
            $user = Auth::user()->id;
            $data_output = Labour::leftJoin('registrationstatus', 'labour.status', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->where('registrationstatus.is_active', true)
                ->where('labour.status', 2)
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
                    'registrationstatus.status_name'
                    

                )->get();

                foreach ($data_output as $labour) {
                    // Append image paths to the output data
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                    $labour->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->aadhar_image;
                    $labour->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->mgnrega_image;
                    $labour->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->voter_image;
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
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Send Approved Labour List fail','error' => $e->getMessage()], 500);
        }
    }

    public function getNotApprovedLabourList(Request $request){
    
        try {
            $user = Auth::user()->id;
            $data_output = Labour::leftJoin('registrationstatus', 'labour.status', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->where('labour.user_id', $user)
                ->where('registrationstatus.is_active', true)
                ->where('labour.status', 3)
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
                    'registrationstatus.status_name'
                    

                )->get();

                foreach ($data_output as $labour) {
                    // Append image paths to the output data
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                    $labour->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->aadhar_image;
                    $labour->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->mgnrega_image;
                    $labour->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->voter_image;
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
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Send Approved Labour List fail','error' => $e->getMessage()], 500);
        }
    }

}
