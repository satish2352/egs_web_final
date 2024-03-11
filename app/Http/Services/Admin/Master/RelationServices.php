<?php
namespace App\Http\Services\Admin\Master;

use App\Http\Repository\Admin\Master\RelationRepository;

use App\Relation;
use Carbon\Carbon;


class RelationServices{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct(){
        $this->repo = new RelationRepository();
    }
    public function getAll(){
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addAll($request) {
        try {
            $add_Relation = $this->repo->addAll($request);
            if ($add_Relation) {
                return ['status' => 'success', 'msg' => 'Relation Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Relation Not Added.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }
    public function getById($id){
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateAll($request){
        try {
            $update_Relation = $this->repo->updateAll($request);
            if ($update_Relation) {
                return ['status' => 'success', 'msg' => 'Relation Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Relation Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }
    public function deleteById($id){
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }
   
    public function updateOne($id)
    {
        return $this->repo->updateOne($id);
    }


}