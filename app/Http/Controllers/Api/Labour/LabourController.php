<?php

namespace App\Http\Controllers\Api\Labour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
// public function add(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'full_name' => 'required',
//         'gender' => 'required',
//         'date_of_birth' => 'required|date_format:d/m/Y',
//         'district_id' => 'required',
//         'taluka_id' => 'required',
//         'village_id' => 'required',
//         'mobile_number' => 'required',
//         'landline_number' => 'required',
//         'mgnrega_card_id' => 'required',
//         'location_id' => 'required',
//         'aadhar_image' => 'required|image',
//         'mgnrega_image' => 'required|image',
//         'profile_image' => 'required|image'
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
//     }

//     try {
//         $labour_data = new Labour();
//         $labour_data->full_name = $request->full_name;
//         $labour_data->gender = $request->gender;
//         $labour_data->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
//         $labour_data->district_id = $request->district_id;
//         $labour_data->taluka_id = $request->taluka_id;
//         $labour_data->village_id = $request->village_id;
//         $labour_data->mobile_number = $request->mobile_number;
//         $labour_data->landline_number = $request->landline_number;
//         $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
//         $labour_data->location_id = $request->location_id;
//         $labour_data->save();

//         $last_insert_id = $labour_data->id;
//         $imageAadhar = $last_insert_id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
//         $imagePancard = $last_insert_id . '_' . rand(100000, 999999) . '_pan.' . $request->mgnrega_image->extension();
//         $imageProfile = $last_insert_id . '_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();

//         $path = Config::get('DocumentConstant.USER_LABOUR_ADD');

//         uploadImage($request, 'aadhar_image', $path, $imageAadhar);
//         uploadImage($request, 'mgnrega_image', $path, $imagePancard);
//         uploadImage($request, 'profile_image', $path, $imageProfile);

//         // Update the image paths in the database
//         $labour_data->aadhar_image = $path . '/' . $imageAadhar;
//         $labour_data->mgnrega_image = $path . '/' . $imagePancard;
//         $labour_data->profile_image = $path . '/' . $imageProfile;
//         $labour_data->save();

//         // Include image paths in the response
//         $labour_data->aadhar_image = $labour_data->aadhar_image;
//         $labour_data->mgnrega_image = $labour_data->mgnrega_image;
//         $labour_data->profile_image = $labour_data->profile_image;

//         return response()->json(['status' => 'success', 'message' => 'Labor added successfully', 'data' => $labour_data], 200);
//     } catch (\Exception $e) {
//         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
//     }
// }
public function add(Request $request )
{
    $validator = Validator::make($request->all(), [
        'full_name' => 'required|alpha',
        'gender_id' => 'required|numeric',
        'date_of_birth' => [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                $dob = Carbon::createFromFormat('d/m/Y', $value);
                if ($dob->isSameDay(now()) || $dob->isAfter(now())) {
                    $fail('The date of birth must be a date before today.');
                }
            },
        ],
        'district_id' => 'required|numeric',
        'taluka_id' => 'required|numeric',
        'village_id' => 'required|numeric',
        'skill_id' => 'required|numeric',
        'mobile_number' => ['required', 'numeric', 'digits:10', 'unique:labour'],
        'landline_number' => ['required', 'regex:/^[0-9]{8,}$/'],
        'mgnrega_card_id' => ['required', 'numeric', 'unique:labour'],
        'latitude' => ['required', 'numeric', 'between:-90,90'], // Latitude range
        'longitude' => ['required', 'numeric', 'between:-180,180'], // Longitude range

    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
    }

    try {
         // Check if the user exists
         $user = User::find($request->user_id);
         if (!$user) {
             return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
         }

        $labour_data = new Labour();
        $labour_data->user_id = $request->user_id; // Assign the user ID
        $labour_data->full_name = $request->full_name;
        $labour_data->gender_id = $request->gender_id;
        $labour_data->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
        $labour_data->district_id = $request->district_id;
        $labour_data->taluka_id = $request->taluka_id;
        $labour_data->village_id = $request->village_id;
        $labour_data->mobile_number = $request->mobile_number;
        $labour_data->landline_number = $request->landline_number;
        $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
        $labour_data->skill_id = $request->skill_id;
        $labour_data->latitude = $request->latitude;
        $labour_data->longitude = $request->longitude;
        $labour_data->aadhar_image = 'null';
        $labour_data->mgnrega_image = 'null';
        $labour_data->profile_image = 'null';
        $labour_data->voter_image = 'null';
        $labour_data->save();

        return response()->json(['status' => 'success', 'message' => 'Labor added successfully', 'data' => $labour_data], 200);
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
        $labour_data->aadhar_image = $path . '/' . $imageAadhar;
        $labour_data->mgnrega_image = $path . '/' . $imagePancard;
        $labour_data->profile_image = $path . '/' . $imageProfile;
        $labour_data->voter_image = $path . '/' . $imageVoter;

        return response()->json(['status' => 'success', 'message' => 'Labor updated successfully', 'data' => $labour_data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
public function getAllLabourList(Request $request){
    try {
        $data_output = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
            ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
            ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
            ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
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

        // Loop through labour data and retrieve family details for each labour
        foreach ($data_output as $labour) {
            $labour->family_details = LabourFamilyDetails::leftJoin('gender as gender_labour', 'labour_family_details.gender_id', '=', 'gender_labour.id')
            ->leftJoin('relation as relation_labour', 'labour_family_details.relationship_id', '=', 'relation_labour.id')
            ->leftJoin('maritalstatus as maritalstatus_labour', 'labour_family_details.married_status_id', '=', 'maritalstatus_labour.id')
                ->select(
                    'labour_family_details.id',
                    'gender_labour.gender_name as gender_name',
                    'relation_labour.relation_title as relation_title',
                    'maritalstatus_labour.maritalstatus as maritalstatus',
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
public function getAllUserLabourList(Request $request){
   
   
    try {
        // echo $request;
        // die();
        $data_output = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
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
            )->get();

        // Loop through labour data and retrieve family details for each labour
        // foreach ($data_output as $labour) {
        //     $labour->family_details = LabourFamilyDetails::where('labour_id', $labour->id)->get();
        // }
        foreach ($data_output as $labour) {
            $labour->family_details = LabourFamilyDetails::leftJoin('gender as gender_labour', 'labour_family_details.gender_id', '=', 'gender_labour.id')
            ->leftJoin('relation as relation_labour', 'labour_family_details.relationship_id', '=', 'relation_labour.id')
            ->leftJoin('maritalstatus as maritalstatus_labour', 'labour_family_details.married_status_id', '=', 'maritalstatus_labour.id')
                ->select(
                    'labour_family_details.id',
                    'gender_labour.gender_name as gender_name',
                    'relation_labour.relation_title as relation_title',
                    'maritalstatus_labour.maritalstatus as maritalstatus',
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
// public function filterLabourList(Request $request){
//     try {
//         $query = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
//             ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
//             ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
//             ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
//             ->when($request->get('user_id'), function($query) use ($request) {
//                 $query->where('labour.user_id',$request->user_id);
//             })
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
//         if ($request->has('district_id')) {
//             $query->where('district_labour.location_id', $request->input('district_id'));
//         }
//         if ($request->has('taluka_id')) {
//             $query->where('taluka_labour.location_id', $request->input('taluka_id'));
//         }
//         if ($request->has('village_id')) {
//             $query->where('village_labour.location_id', $request->input('village_id'));
//         }

//         $data_output = $query->get();

//         return response()->json(['status' => 'success', 'message' => 'Filtered data retrieved successfully', 'data' => $data_output], 200);
//     } catch (\Exception $e) {
//         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
//     }
// }

public function filtermgnregaIdLabourList(Request $request){
    try {
        $query = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
            ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
            ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
            ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
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
        // if ($request->has('mgnrega_card_id')) {
        //     $query->where('labour.mgnrega_card_id', 'like', '%' . $request->input('mgnrega_card_id') . '%');
        // }
      
        $data_output = $query->get();

        return response()->json(['status' => 'success', 'message' => 'Filtered data retrieved successfully', 'data' => $data_output], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// public function getParticularLabour(Request $request, $id) {
//     try {
//         $labour = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
//             ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
//             ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
//             ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
//             ->where('labour.id', $id) // Filter by laborer ID
//             ->select(
//                 'labour.id',
//                 'labour.full_name',
//                 'labour.date_of_birth',
//                 'gender_labour.gender_name as gender_name',
//                 'district_labour.name as district_name',
//                 'taluka_labour.name as taluka_name',
//                 'village_labour.name as village_name',
//                 'labour.mobile_number',
//                 'labour.landline_number',
//                 'labour.mgnrega_card_id',
//                 'labour.latitude',
//                 'labour.longitude',
//                 'labour.profile_image',
//                 'labour.aadhar_image',
//                 'labour.mgnrega_image'
//             )->first(); // Use first() instead of get() to retrieve a single record

//         if (!$labour) {
//             return response()->json(['status' => 'error', 'message' => 'Labour details not found'], 404);
//         }

//         // Retrieve family details for the laborer
//         $labour->family_details = LabourFamilyDetails::where('labour_id', $id)->get();

//         return response()->json(['status' => 'success', 'message' => 'Labour data retrieved successfully', 'data' => $labour], 200);
//     } catch (\Exception $e) {
//         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
//     }
// }


}
