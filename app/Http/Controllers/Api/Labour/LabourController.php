<?php

namespace App\Http\Controllers\Api\Labour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Labour;
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
    //         'date_of_birth' => 'required',
    //         'district_id' => 'required',
    //         'taluka_id' => 'required',
    //         'village_id' => 'required',
    //         'mobile_number' => 'required',
    //         'landline_number' => 'required',
    //         'mgnrega_card_id' => 'required',
    //         'location_id' => 'required',
    //         // 'aadhar_image' => 'required',
    //         // 'pancard_image' => 'required',
    //         // 'profile_image' => 'required'

    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
    //     }

    //     try {
    //         $labour_data = new Labour();
    //         $labour_data->full_name = $request->full_name;
    //         $labour_data->gender = $request->gender;
    //         $labour_data->date_of_birth = $request->date_of_birth;
    //         $labour_data->district_id = $request->district_id;
    //         $labour_data->taluka_id = $request->taluka_id;
    //         $labour_data->village_id = $request->village_id;
    //         $labour_data->mobile_number = $request->mobile_number;
    //         $labour_data->landline_number = $request->landline_number;
    //         $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
    //         $labour_data->location_id = $request->location_id;

    //         $labour_data->save();
            
    //         return response()->json(['status' => 'success', 'message' => 'Role added successfully', 'data' => $labour_data], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    //     }
    // }

//     public function add(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'full_name' => 'required',
//         'gender' => 'required',
//         'date_of_birth' => 'required',
//         'district_id' => 'required',
//         'taluka_id' => 'required',
//         'village_id' => 'required',
//         'mobile_number' => 'required',
//         'landline_number' => 'required',
//         'mgnrega_card_id' => 'required',
//         'location_id' => 'required',
//         'aadhar_image' => 'required|image',
//         'pancard_image' => 'required|image',
//         'profile_image' => 'required|image'
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
//     }

//     try {
//         $labour_data = new Labour();
//         $labour_data->full_name = $request->full_name;
//         $labour_data->gender = $request->gender;
//         $labour_data->date_of_birth = $request->date_of_birth;
//         $labour_data->district_id = $request->district_id;
//         $labour_data->taluka_id = $request->taluka_id;
//         $labour_data->village_id = $request->village_id;
//         $labour_data->mobile_number = $request->mobile_number;
//         $labour_data->landline_number = $request->landline_number;
//         $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
//         $labour_data->location_id = $request->location_id;
//         $labour_data->save();


//         $labour_data->save();

// 		$last_insert_id = $labour_data->id;
// 		// $this->insertRolesPermissions($request, $last_insert_id);
// 		$imageAadhar = $last_insert_id .'_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
// 		$imagePancard = $last_insert_id .'_' . rand(100000, 999999) . '_pan.' . $request->pancard_image->extension();
// 		$imageProfile = $last_insert_id .'_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();
        
//         $user_detail = Labour::find($last_insert_id); // Assuming $request directly contains the ID
//         $user_detail->aadhar_image = $imageAadhar;
//         $user_detail->pancard_image = $imagePancard;
//         $user_detail->profile_image = $imageProfile; // Save the image filename to the database
      
      
//         $path = Config::get('DocumentConstant.USER_LABOUR_ADD');
//         dd
//         $englishImageName = $last_id['englishImageName'];
//         $marathiImageName = $last_id['marathiImageName'];
//         uploadImage($request, 'english_image', $path, $englishImageName);
//         uploadImage($request, 'marathi_image', $path, $marathiImageName);  $user_detail->save();
//         // echo  $user_detail;
// 		// die();
//         $data['imageAadhar'] =$imageAadhar;
//         $data['imagePancard'] =$imagePancard;
//         $data['imageProfile'] =$imageProfile;
       
//         return response()->json(['status' => 'success', 'message' => 'Labor added successfully', 'data' => $labour_data], 200);
//     } catch (\Exception $e) {
//         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
//     }
// }
public function add(Request $request)
{
    $validator = Validator::make($request->all(), [
        'full_name' => 'required',
        'gender' => 'required',
        'date_of_birth' => 'required|date_format:d/m/Y',
        'district_id' => 'required',
        'taluka_id' => 'required',
        'village_id' => 'required',
        'mobile_number' => 'required',
        'landline_number' => 'required',
        'mgnrega_card_id' => 'required',
        'location_id' => 'required',
        'aadhar_image' => 'required|image',
        'pancard_image' => 'required|image',
        'profile_image' => 'required|image'
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
    }

    try {
        $labour_data = new Labour();
        $labour_data->full_name = $request->full_name;
        $labour_data->gender = $request->gender;
        $labour_data->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
        $labour_data->district_id = $request->district_id;
        $labour_data->taluka_id = $request->taluka_id;
        $labour_data->village_id = $request->village_id;
        $labour_data->mobile_number = $request->mobile_number;
        $labour_data->landline_number = $request->landline_number;
        $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
        $labour_data->location_id = $request->location_id;
        $labour_data->save();

        $last_insert_id = $labour_data->id;
        $imageAadhar = $last_insert_id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
        $imagePancard = $last_insert_id . '_' . rand(100000, 999999) . '_pan.' . $request->pancard_image->extension();
        $imageProfile = $last_insert_id . '_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();

        $path = Config::get('DocumentConstant.USER_LABOUR_ADD');

        uploadImage($request, 'aadhar_image', $path, $imageAadhar);
        uploadImage($request, 'pancard_image', $path, $imagePancard);
        uploadImage($request, 'profile_image', $path, $imageProfile);

        // Update the image paths in the database
        $labour_data->aadhar_image = $path . '/' . $imageAadhar;
        $labour_data->pancard_image = $path . '/' . $imagePancard;
        $labour_data->profile_image = $path . '/' . $imageProfile;
        $labour_data->save();

        // Include image paths in the response
        $labour_data->aadhar_image = $labour_data->aadhar_image;
        $labour_data->pancard_image = $labour_data->pancard_image;
        $labour_data->profile_image = $labour_data->profile_image;

        return response()->json(['status' => 'success', 'message' => 'Labor added successfully', 'data' => $labour_data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}


}
