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
    Documenttype,
    Registrationstatus
};

class AllMasterController extends Controller
{
public function getAllMasters(){
    try {
        $data = [];
        $data['gender'] = Gender::orderBy('id', 'asc')->get();
        $data['maritalstatus'] = Maritalstatus::orderBy('id', 'asc')->get();
        $data['skills'] = Skills::all();
        $data['relation'] = RelationModel::orderBy('id', 'asc')->get();
        $data['documenttype'] = Documenttype::all();
        $data['Registrationstatus'] = Registrationstatus::orderBy('id', 'asc')->get();
        return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

}


