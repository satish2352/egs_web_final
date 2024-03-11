<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Documenttype
};

class DocumenttypeRepository{
	public function getAll(){
        try {
            return Documenttype::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $documenttype_data = new Documenttype();
            $documenttype_data->documenttype = $request['documenttype'];
            $documenttype_data->save();       
                
            return $documenttype_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $documenttype = Documenttype::find($id);
            if ($documenttype) {
                return $documenttype;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id document type.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $documenttype_data = Documenttype::find($request->id);
            
            if (!$documenttype_data) {
                return [
                    'msg' => 'document type data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $documenttype_data->documenttype = $request['documenttype'];
            // $documenttype_data->marathi_title = $request['marathi_title'];
            // $documenttype_data->url = $request['url'];
            $documenttype_data->save();        
        
            return [
                'msg' => 'document type data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update documenttype data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id) {
        try {
            $documenttype = Documenttype::find($id);
            if ($documenttype) {
                // Delete the images from the storage folder
                Storage::delete([
                    'public/images/citizen-action/documenttype-suggestion/'.$documenttype->english_image,
                    'public/images/citizen-action/documenttype-suggestion/'.$documenttype->marathi_image
                ]);

                // Delete the record from the database
                
                $documenttype->delete();
                
                return $documenttype;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne($request){
        try {
            $slide = Documenttype::find($request); // Assuming $request directly contains the ID

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