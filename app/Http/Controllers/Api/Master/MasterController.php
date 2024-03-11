<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	Gender,
    Maritalstatus
};

class MasterController extends Controller
{
public function getAllGender(){
    try {
        $genders = Gender::all();
        return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $genders], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
public function getAllMaritalStatus(){
    try {
        $maritalstatus = Maritalstatus::all();
        return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $maritalstatus], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

}


