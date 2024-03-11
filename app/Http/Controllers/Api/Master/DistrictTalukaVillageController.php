<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\TblArea;

class DistrictTalukaVillageController extends Controller
{

    // public function getState(Request $request)
    // {
    //     $stateId = $request->input('stateId');
    //     $state =  TblArea::where('location_type', 1)
    //                         // ->where('parent_id', $stateId)
    //                         ->select('location_id','name')
    //                         ->get()
    //                         ->toArray();
    //     return response()->json(['state' => $state]);

    // }
    public function getState($countryId)
{
    try {
        $states = TblArea::where('location_type', 1)
                         ->where('parent_id', $countryId) // Assuming parent_id represents the country ID
                         ->select('location_id', 'name')
                         ->get()
                         ->toArray();

        return response()->json([
            'status' => 'success',
            'message' => 'State list retrieved successfully',
            'data' => $states
        ]);
    } catch (\Exception $e) {
        // Handle the exception, you can log it or return an error response
        return response()->json(['error' => 'An error occurred while fetching states.'], 500);
    }
}
public function getDistrict(Request $request, $stateId)
{
    try {
        // Validate input
        $request->validate([
            // You can add validation rules if needed
        ]);

        // Fetch cities
        $district = TblArea::where('location_type', 2) // Assuming 2 represents cities
                        ->where('parent_id', $stateId)
                        ->pluck('name', 'location_id') // Pluck name and location_id
                        ->toArray(); // Convert to array

        return response()->json([
            'status' => 'success',
            'message' => 'District list retrieved successfully',
            'data' => $district
        ]);
    } catch (\Exception $e) {
        // Handle the exception, you can log it or return an error response
        return response()->json(['error' => 'An error occurred while fetching cities.'], 500);
    }
}
public function getTaluka(Request $request, $districtId)
{
    try {
        // You can add validation rules if needed

        // Fetch talukas
        $talukas = TblArea::where('location_type', 3) // Assuming 3 represents talukas
                        ->where('parent_id', $districtId)
                        ->pluck('name', 'location_id') // Pluck name and location_id
                        ->toArray(); // Convert to array

                      
        return response()->json([
            'status' => 'success',
            'message' => 'Taluka list retrieved successfully',
            'data' => $talukas
        ]);
    } catch (\Exception $e) {
        // Handle the exception, log it for debugging
        Log::error('An error occurred while fetching talukas: ' . $e->getMessage());
        
        return response()->json(['error' => 'An error occurred while fetching talukas.'], 500);
    }
}
public function getVillage(Request $request, $talukaId)
{
    try {
        // You can add validation rules if needed

        // Fetch talukas
        $village = TblArea::where('location_type', 4) // Assuming 3 represents talukas
                        ->where('parent_id', $talukaId)
                        ->pluck('name', 'location_id') // Pluck name and location_id
                        ->toArray(); // Convert to array

                      
        return response()->json([
            'status' => 'success',
            'message' => 'Village list retrieved successfully',
            'data' => $village
        ]);
    } catch (\Exception $e) {
        // Handle the exception, log it for debugging
        Log::error('An error occurred while fetching talukas: ' . $e->getMessage());
        
        return response()->json(['error' => 'An error occurred while fetching talukas.'], 500);
    }
}

}


