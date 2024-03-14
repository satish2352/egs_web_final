<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Labour
};
use Illuminate\Validation\Rule;
use App\Http\Services\Admin\Menu\RoleServices;
use Validator;
class ReportsController extends Controller
{

    public function __construct()
    {
        $this->service = new RoleServices();
    }
    public function getAllLocation()
    {
        try {
            // $roles = $this->service->getAllRole();
            return view('admin.pages.reports.list-location-report');
        } catch (\Exception $e) {
            return $e;
        }
    }
 

   

}