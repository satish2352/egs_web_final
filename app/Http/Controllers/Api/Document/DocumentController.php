<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
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
    public function add(Request $request )
    {
        $all_data_validation = [
            'full_name' => 'required|alpha',
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
            
            'mgnrega_card_id' => ['required', 'numeric', 'unique:labour'],
            'latitude' => ['required', 'numeric', 'between:-90,90'], // Latitude range
            'longitude' => ['required', 'numeric', 'between:-180,180'], // Longitude range
            'aadhar_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
            'mgnrega_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048', 
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048',
            'voter_image' => 'required|image|mimes:jpeg,png,jpg,gif|min:10|max:2048',

            'family' => 'required|array',
            'family.*.full_name' => 'required|string',
            'family.*.gender_id' => 'required|integer',
            'family.*.relationship_id' => 'required|integer',
            'family.*.married_status_id' => 'required|integer',
            'family.*.date_of_birth' => [
                'required',
                'date_format:d/m/Y',
                function ($attribute, $value, $fail) {
                    $dob = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
                    if ($dob->isAfter(\Carbon\Carbon::now())) {
                        $fail('The date of birth must be a date before today.');
                    }
                },
            ],

        ];


        if(isset($request->landline_number)) {
            $all_data_validation['landline_number'] =  ['required', 'regex:/^[0-9]{8,}$/'];
        }
        $validator = Validator::make($request->all(), $all_data_validation);


        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }

        try {
            // Check if the user exists
            $user = Auth::user();

            $labour_data = new Labour();
            $labour_data->user_id = $user->id; // Assign the user ID
            $labour_data->full_name = $request->full_name;
           
            $labour_data->save();
            $last_insert_id = $labour_data->id;
            $imageAadhar = $last_insert_id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
        

            $path = Config::get('DocumentConstant.USER_LABOUR_ADD');

            uploadImage($request, 'aadhar_image', $path, $imageAadhar);
          

            // Update the image paths in the database
            $labour_data->aadhar_image =  $imageAadhar;
           
            $labour_data->save();

            // Include image paths in the response
            $labour_data->aadhar_image = $labour_data->aadhar_image;
            
            return response()->json(['status' => 'success', 'message' => 'Labor added successfully',  'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}
