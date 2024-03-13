<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Relation,
    Permissions,
    RolesPermissions
};

class RelationRepository{
	public function getAll(){
        try {
            return Relation::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $relation_data = new Relation();
            $relation_data->Relation = $request['relation_title'];
            $relation_data->save();       
        // dd($relation_data);
                
            return $relation_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $relation = Relation::find($id);
            if ($relation) {
                return $relation;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id Relation.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $relation_data = Relation::find($request->id);
            
            if (!$relation_data) {
                return [
                    'msg' => 'Relation data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $relation_data->Relation = $request['relation_title'];
            // $relation_data->marathi_title = $request['marathi_title'];
            // $relation_data->url = $request['url'];
            $relation_data->save();        
        
            return [
                'msg' => 'Relation data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update Relation data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id) {
        try {
            $relation = Relation::find($id);
            if ($relation) {
                // Delete the images from the storage folder
                Storage::delete([
                    'public/images/citizen-action/relation-suggestion/'.$relation->english_image,
                    'public/images/citizen-action/relation-suggestion/'.$relation->marathi_image
                ]);

                // Delete the record from the database
                
                $relation->delete();
                
                return $relation;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne($request){
        try {
            $slide = Relation::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the Slider model
            if ($slide) {
                $is_active = $slide->is_active === 1 ? 0 : 1;
                $slide->is_active = $is_active;
                $slide->save();

                return [
                    'msg' => 'Slide updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Slide not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update slide.',
                'status' => 'error'
            ];
        }
    }
    
       
}