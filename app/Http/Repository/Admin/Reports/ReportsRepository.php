<?php
namespace App\Http\Repository\Admin\Reports;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Roles,
    Permissions,
    RolesPermissions

};

class ReportsRepository  {
	public function getAllRole()
    {
        try {
            return Roles::all();
        } catch (\Exception $e) {
            return $e;
        }
    }


}