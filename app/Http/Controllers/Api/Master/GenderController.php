<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Gender;

class GenderController extends Controller
{
    public function getAllGender()
{
    try {
        $genders = Gender::all();
       esponse()->json(['status' => 'success', 'message' => 'All genders retrieved successfully', 'data' => $genders], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}


