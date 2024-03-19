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
    
            $document_data = new GramPanchayatDocuments();
            $document_data->user_id = $user->id; // Assign the user ID
            $document_data->document_type_id = $request->document_type_id;
    
            $document_data->save();
            $last_insert_id = $document_data->id;
            $documentPdf = $last_insert_id . '_' . rand(100000, 999999) . '_document.pdf';
    
            $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
    
            // Assuming you have a function named uploadDocument which handles PDF uploads
            uploadImage($request, 'document_pdf', $path, $documentPdf);
    
            // Update the document path in the database
            $document_data->document_pdf = $documentPdf;
            $document_data->save();
    
            // Include document path in the response
            $document_data->document_pdf = $document_data->document_pdf;
    
            return response()->json(['status' => 'success', 'message' => 'Document Uploaded successfully',  'data' => $document_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    

}
