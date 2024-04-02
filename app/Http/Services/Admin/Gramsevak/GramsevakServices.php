<?php
namespace App\Http\Services\Admin\Gramsevak;

use App\Http\Repository\Admin\Gramsevak\GramsevakRepository;


use App\Models\
{ 
    User,
    Labour 
};
use Carbon\Carbon;
use Config;
use Storage;

class GramsevakServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct() {
        $this->repo = new GramsevakRepository();
    }

    public function index() {
        $data_gramsevaks = $this->repo->getGramsevakList();
        return $data_gramsevaks;
    }

    public function showGramsevakDocuments($id){
        try {
            return $this->repo->showGramsevakDocuments($id);
        } catch (\Exception $e) {
            return $e;
        }
    }


}