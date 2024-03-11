<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Roles;

class RoleController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }

        try {
            $role = new Roles();
            $role->role_name = $request->role_name;
            $role->save();
            
            return response()->json(['status' => 'success', 'message' => 'Role added successfully', 'data' => $role], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}


