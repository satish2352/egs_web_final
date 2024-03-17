<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	Gender,
    Maritalstatus,
    Skills,
    RelationModel,
    Documenttype
};

class AllMasterController extends Controller
{
public function getAllMasters(){
    try {
        $data = [];
        $data['gender'] = Gender::all();
        $data['maritalstatus'] = Maritalstatus::all();
        $data['skills'] = Skills::all();
        $data['relation'] = RelationModel::all();
        $data['documenttype'] = Documenttype::all();
       
        return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

}


