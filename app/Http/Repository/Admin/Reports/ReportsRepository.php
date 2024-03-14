<?php
namespace App\Http\Repository\Admin\Reports;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
    Labour

};

class ReportsRepository  {

    public function getAllLabourLocation(){
        try {
            
            // $data_output= Labour::get();
            $data_output = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
        // ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
        //   ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
        //   ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
        //   ->where('gender.is_active', true)
          ->select(
              'labour.id',
              'labour.full_name',
              'labour.date_of_birth', 
              'gender_labour.gender_name as name',
            //   'district_labour.name as district_id',
            //   'taluka_labour.name as taluka_id',
            //   'village_labour.name as village_id',
              'labour.mobile_number',
              'labour.landline_number',
              'labour.mgnrega_card_id',
              'labour.latitude',
              'labour.longitude',
            //   'labour.location_id',
            //   'labour.profile_image',
            //   'labour.aadhar_image',
            //   'labour.pancard_image',
          )->get();
            return $data_output;
        } catch (\Exception $e) {
      
            return $e;
        }
    }
}