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
    public function add(Request $request){
        $all_data_validation = [
            'document_type_id' => 'required',  
            'document_name' => 'required', 
            'document_pdf' => 'required|mimes:pdf|min:1|max:10240', // 10MB max size
        ];       
        $validator = Validator::make($request->all(), $all_data_validation);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }
        try {
            $user = Auth::user();
            $document_data = new GramPanchayatDocuments();
            $document_data->user_id = $user->id; 
            $document_data->document_name = $request->document_name;
            $document_data->document_type_id = $request->document_type_id;
    
            $document_data->save();
            $last_insert_id = $document_data->id;
            $documentPdf = $last_insert_id . '_' . rand(100000, 999999) . '_document.pdf';
    
            $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
    
            uploadImage($request, 'document_pdf', $path, $documentPdf);
    
            $document_data->document_pdf = $documentPdf;
            $document_data->save();
    
            $document_data->document_pdf = $document_data->document_pdf;
    
            return response()->json(['status' => 'true', 'message' => 'Document Uploaded successfully',  'data' => $document_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document Uploaded failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function getAllDocuments(Request $request){
    
        try {
            $user = Auth::user()->id;
            $data_output = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
                ->where('tbl_gram_panchayat_documents.user_id', $user)
                ->when($request->has('documenttype'), function($query) use ($request) {
                    $query->where('tbl_documenttype.documenttype', 'like', '%' . $request->documenttype . '%');
                })
                ->select(
                    'tbl_gram_panchayat_documents.id',
                    'tbl_gram_panchayat_documents.document_name',
                    'tbl_documenttype.documenttype',
                    'tbl_gram_panchayat_documents.document_pdf',
                )->get();

                  
                
                foreach ($data_output as $document_data) {
                    // Append image paths to the output data
                    $document_data->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document_data->document_pdf;
                }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document List Get Fail', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateDocuments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'document_type_id' => 'required',
                'document_name' => 'required',             
                'document_pdf' => 'required|mimes:pdf|min:1|max:10240', 
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
            }
    
            $document_data = GramPanchayatDocuments::findOrFail($request->id);
    
            // Delete old document PDF if it exists
            if (!empty($document_data->document_pdf)) {
                $old_pdf_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_DELETE') . $document_data->document_pdf;
                if (file_exists_view($old_pdf_path)) {
                    removeImage($old_pdf_path);
                }
            }
    
            // Upload the new document PDF
            $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
            $new_pdf_name = $request->id . '_' . rand(100000, 999999) . '.' . $request->document_pdf->extension();
            uploadImage($request, 'document_pdf', $path, $new_pdf_name);
    
            // Update document information in the database
            $document_data->document_type_id = $request->document_type_id;
            $document_data->document_pdf = $new_pdf_name;
            $document_data->save();
    
            return response()->json(['status' => 'success', 'message' => 'Document updated successfully', 'data' => $document_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
}
