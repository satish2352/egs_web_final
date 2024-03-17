<?php

namespace App\Http\Controllers\Api\Labour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	LabourAttendanceMark
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;



class LabourAttendanceMarkController extends Controller
{

public function addAttendanceMark(Request $request )
{
    $validator = Validator::make($request->all(), [
      

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
      
        $labour_data->save();

        return response()->json(['status' => 'success', 'message' => 'Labor added successfully', 'data' => $labour_data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}
