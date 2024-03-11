<?php
namespace App\Http\Services\Admin\Master;

use App\Http\Repository\Admin\Master\DocumenttypeRepository;

use App\Documenttype;
use Carbon\Carbon;


class DocumenttypeServices{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct(){
        $this->repo = new DocumenttypeRepository();
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
            $add_Documenttype = $this->repo->addAll($request);
            if ($add_Documenttype) {
                return ['status' => 'success', 'msg' => 'Document type Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Document type Not Added.'];
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
            $update_Documenttype = $this->repo->updateAll($request);
            if ($update_Documenttype) {
                return ['status' => 'success', 'msg' => 'Documenttype Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Documenttype Not Updated.'];
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