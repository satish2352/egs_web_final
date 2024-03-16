<?php
namespace App\Http\Controllers\Api\Labour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	LabourFamilyDetails,
    Labour
};

class LabourFamilyDetailsController extends Controller
{
    // public function add(Request $request, $labour_id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'full_name' => 'required',
    //         'gender_id' => 'required',
    //         'relationship_id' => 'required',
    //         'married_status_id' => 'required',
    //         'date_of_birth' => 'required|date_format:d/m/Y',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
    //     }
    
    //     try {
    //         // Create a new LabourFamilyDetails instance
    //         $familyDetails = new LabourFamilyDetails();
    //         $familyDetails->labour_id = $labour_id; // Assign the labour ID from the URL parameter
    //         $familyDetails->full_name = $request->full_name;
    //         $familyDetails->gender_id = $request->gender_id;
    //         $familyDetails->relationship_id = $request->relationship_id;
    //         $familyDetails->married_status_id = $request->married_status_id;
    //         $familyDetails->date_of_birth = $request->date_of_birth;
    //         $familyDetails->save();
    
    //         return response()->json(['status' => 'success', 'message' => 'Family details added successfully', 'data' => $familyDetails], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    //     }
    // }
    
public function add(Request $request, $labour_id)
{
    try {
        $rules = [
            'full_name' => 'required|array',
            'full_name.*' => 'required|string',
            'gender_id' => 'required|array',
            'gender_id.*' => 'required|integer',
            'relationship_id' => 'required|array',
            'relationship_id.*' => 'required|integer',
            'married_status_id' => 'required|array',
            'married_status_id.*' => 'required|integer',
            'date_of_birth.*' => [
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

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        
        $familyDetails = [];

        foreach ($request->input('full_name') as $index => $fullName) {
            $familyDetail = new LabourFamilyDetails();
            $familyDetail->labour_id = $labour_id;
            $familyDetail->full_name = $fullName;
            $familyDetail->gender_id = $request->input("gender_id.$index");
            $familyDetail->relationship_id = $request->input("relationship_id.$index");
            $familyDetail->married_status_id = $request->input("married_status_id.$index");
            $familyDetail->date_of_birth = $request->input("date_of_birth.$index");
            $familyDetail->save();

            $familyDetails[] = $familyDetail;
        }

        return response()->json(['status' => 'success', 'message' => 'Family details added successfully', 'data' => $familyDetails], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}


}