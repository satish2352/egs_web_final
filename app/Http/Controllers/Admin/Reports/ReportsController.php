<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Labour
};
use Illuminate\Validation\Rule;
use App\Http\Services\Admin\Reports\ReportsServices;
use Validator;
class ReportsController extends Controller
{

    public function __construct()
    {
        $this->service = new ReportsServices();
    }
    public function getAllLabourLocation()
    {
        try {
            $getOutput = $this->service->getAllLabourLocation();
            // dd($getOutput);
            return view('admin.pages.reports.list-location-report', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }
 

    public function getAllLabourDuration()
    {
        try {
            // $getOutput = $this->service->getAllLabourLocation();
            // dd($getOutput);
            return view('admin.pages.reports.list-labour-duration-report');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllProjects()
    {
        try {
            // $getOutput = $this->service->getAllLabourLocation();
            // dd($getOutput);
            return view('admin.pages.reports.list-project-report');
        } catch (\Exception $e) {
            return $e;
        }
    }
   

    public function getAllProjectLocation()
    {
        try {
            // $getOutput = $this->service->getAllLabourLocation();
            // dd($getOutput);
            return view('admin.pages.reports.list-project-and-location-report');
        } catch (\Exception $e) {
            return $e;
        }
    }
}