<?php

namespace App\Http\Controllers\Api\Documents;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	Documenttype,
    GramPanchayatDocuments
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;

class GramPanchayatDocumentController extends Controller
{
    public function add(Request $request)
    {
        $all_data_validation = [
            'document_type_id' => 'required',            
            'document_pdf' => 'required|mimes:pdf|min:1|max:10240', // 10MB max size
        ];       
        $validator = Validator::make($request->all(), $all_data_validation);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }
    
        try {
            // Check if the user exists
            $user = Auth::user();
    
            $labour_data = new GramPanchayatDocuments();
            $labour_data->user_id = $user->id; // Assign the user ID
            $labour_data->document_type_id = $request->document_type_id;
    
            $labour_data->save();
            $last_insert_id = $labour_data->id;
            $documentPdf = $last_insert_id . '_' . rand(100000, 999999) . '_document.pdf';
    
            $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
    
            // Assuming you have a function named uploadDocument which handles PDF uploads
            uploadDocument($request, 'document_pdf', $path, $documentPdf);
    
            // Update the document path in the database
            $labour_data->document_pdf = $documentPdf;
            $labour_data->save();
    
            // Include document path in the response
            $labour_data->document_pdf = $labour_data->document_pdf;
    
            return response()->json(['status' => 'success', 'message' => 'Labor added successfully',  'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    

}
