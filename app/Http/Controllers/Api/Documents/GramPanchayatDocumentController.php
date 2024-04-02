<?php

namespace App\Http\Controllers\Api\Documents;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
	Documenttype,
    GramPanchayatDocuments,
    HistoryDocumentModel
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class GramPanchayatDocumentController extends Controller
{
    public function add(Request $request){
        $all_data_validation = [
            'document_type_id' => 'required',  
            'document_name' => 'required', 
            'document_pdf' => 'required|mimes:pdf|min:1|max:10240', 
            'latitude' => ['required', 'between:-90,90'], // Latitude range
            'longitude' => ['required', 'between:-180,180'], // Longitude range
        ];       
        $validator = Validator::make($request->all(), $all_data_validation);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }
        try {
            $user = Auth::user();
            // date_default_timezone_set('Asia/Kolkata'); 
            $currentDateTime = date('YmdHis');
            // $currentDateTime = date('Ymd_His');
            
            $document_data = new GramPanchayatDocuments();
            $document_data->user_id = $user->id; 
            $document_data->document_name = $request->document_name;
            $document_data->document_type_id = $request->document_type_id;
            $document_data->latitude = $request->latitude;
            $document_data->longitude = $request->longitude;
            $document_data->is_approved = 1;
            $document_data->is_resubmitted = true;
            $document_data->reason_doc_id = null;
            $document_data->save();
            $last_insert_id = $document_data->document_name;
            // $documentPdf = $last_insert_id . '_' . rand(100000, 999999) . '_document.pdf';
            $documentPdf = $last_insert_id;
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
            $data_output = [];

            $user = Auth::user()->id;
            $is_approved = '' ;

            if($request->has('is_approved') && $request->is_approved == 'added') {  //1
                $is_approved = 1 ;
            } elseif($request->has('is_approved') && $request->is_approved == 'not_approved') { //3
                $is_approved = 3 ;
            } elseif($request->has('is_approved') && $request->is_approved == 'approved') { //3
                $is_approved = 2 ;
            } 
            elseif($request->has('is_resubmitted') && $request->is_resubmitted == 'resubmitted') { //3
                $is_resubmitted = 1 ;
            } 
            $data_output = GramPanchayatDocuments::leftJoin('registrationstatus', 'tbl_gram_panchayat_documents.is_approved', '=', 'registrationstatus.id')
            ->leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
            ->leftJoin('users', 'tbl_gram_panchayat_documents.user_id', '=', 'users.id')
            ->leftJoin('tbl_area as district_u', 'users.user_district', '=', 'district_u.location_id')
            ->leftJoin('tbl_area as taluka_u', 'users.user_taluka', '=', 'taluka_u.location_id')
            ->leftJoin('tbl_area as village_u', 'users.user_village', '=', 'village_u.location_id')
                ->where('tbl_gram_panchayat_documents.user_id', $user)
                ->when($request->has('document_type_name'), function($query) use ($request) {
                    $query->where('tbl_documenttype.document_type_name', 'like', '%' . $request->document_type_name . '%');
                })
                ->when($request->has('gram_document_id'), function($query) use ($request) {
                    $query->where('tbl_gram_panchayat_documents.id',$request->gram_document_id);
                })
                ->when($request->has('is_approved'), function($query) use ($is_approved) {
                    $query->where('tbl_gram_panchayat_documents.is_approved', $is_approved);
                });
                if ($request->has('district_id')) {
                    $data_output->where('district_u.location_id', $request->input('district_id'));
                }
                if ($request->has('taluka_id')) {
                    $data_output->where('taluka_u.location_id', $request->input('taluka_id'));
                }
                if ($request->has('village_id')) {
                    $data_output->where('village_u.location_id', $request->input('village_id'));
                }
                $data_output = $data_output->select(
                    'tbl_gram_panchayat_documents.id',
                    'tbl_gram_panchayat_documents.document_name',
                    'tbl_documenttype.document_type_name',
                    'tbl_gram_panchayat_documents.document_pdf',
                    'users.user_district',
                    'district_u.name as district_name',
                    'users.user_taluka',
                    'taluka_u.name as taluka_name',
                    'users.user_village',
                    'village_u.name as village_name',
                    'registrationstatus.status_name',
                    'tbl_gram_panchayat_documents.updated_at',
                )->get();
                foreach ($data_output as $document_data) {
                    $document_data->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document_data->document_pdf;
                }

                foreach ($data_output as &$documenthistory) {
                    $documenthistory['history_details'] = HistoryDocumentModel::leftJoin('roles', 'tbl_doc_history.roles_id', '=', 'roles.id')
                        ->leftJoin('users', 'tbl_doc_history.user_id', '=', 'users.id')
                        ->leftJoin('tbl_doc_reason', 'tbl_doc_history.reason_doc_id', '=', 'tbl_doc_reason.id')
                        ->leftJoin('tbl_gram_panchayat_documents', 'tbl_doc_history.gram_document_id', '=', 'tbl_gram_panchayat_documents.id')
                        ->select(
                            'tbl_doc_history.id',
                            'roles.role_name as role_name',
                            'users.f_name as f_name',
                            'tbl_doc_reason.reason_name as reason_name',
                            'tbl_doc_history.other_remark',
                            'tbl_doc_history.updated_at',
                        )
                        ->where('tbl_doc_history.gram_document_id', $documenthistory['id'])
                        ->get();
                }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document List Get Fail', 'error' => $e->getMessage()], 500);
        }
    }
   
    // public function updateDocuments(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'document_type_id' => 'required',
    //             'document_name' => 'required',             
    //             'document_pdf' => 'required|mimes:pdf|min:1|max:10240', 
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
    //         }          
    //         $document_data = GramPanchayatDocuments::where('id', $request->id)
    //         ->first();
    
    //         // $last_insert_id = $document_data->document_name;
    //         // $documentPdf = $last_insert_id;
           
    //         if (!empty($document_data->document_pdf)) {
    //             $old_pdf_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_DELETE') . $document_data->document_pdf;
    //             if (file_exists_view($old_pdf_path)) {
    //                 removeImage($old_pdf_path);
    //             }
    //         }
           

    //         $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
    //         uploadImage($request, 'document_pdf', $path, $documentPdf);
    
    //         // Update document information in the database
    //         $document_data->document_type_id = $request->document_type_id;
    //         $document_data->document_name = $request->document_name;
    //         $document_data->document_pdf =  $documentPdf;
    //         $document_data->save();
    
    //         return response()->json(['status' => 'true', 'message' => 'Document updated successfully', 'data' => $document_data], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Document updated fail', 'error' => $e->getMessage()], 500);
    //     }
    // }
    public function updateDocuments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required', 
                // 'document_type_id' => 'required',
                // 'document_name' => 'required',             
                'document_pdf' => 'required|mimes:pdf|min:1|max:10240', 
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
            }          
            
            
            $document_data = GramPanchayatDocuments::findOrFail($request->id);
    
            
            if (!empty($document_data->document_pdf)) {
                $old_pdf_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_DELETE') . $document_data->document_pdf;
                if (file_exists_view($old_pdf_path)) {
                    removeImage($old_pdf_path);
                }
            }
           
            $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
            $documentPdf = $request->document_name; 
            uploadImage($request, 'document_pdf', $path, $documentPdf);
    
            // Update document information in the database
            // $document_data->document_type_id = $request->document_type_id;
            // $document_data->document_name = $request->document_name;
            $document_data->latitude = $request->latitude;
            $document_data->longitude = $request->longitude;
            $document_data->document_pdf =  $documentPdf;
            $document_data->save();
    
            return response()->json(['status' => 'true', 'message' => 'Document updated successfully', 'data' => $document_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document updated fail', 'error' => $e->getMessage()], 500);
        }
    }
    

    public function getAllDocumentsOfficer(Request $request){
        try {
            $user = Auth::user()->id;

            $fromDate = date('Y-m-d', strtotime($request->input('from_date')));
            $fromDate =  $fromDate.' 00:00:01';
            $toDate = date('Y-m-d', strtotime($request->input('to_date')));
            $toDate =  $toDate.' 23:59:59';

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
            ->where('users.id', $user)
            ->first();

        $utype=$data_output->user_type;
        $user_working_dist=$data_output->user_district;
        $user_working_tal=$data_output->user_taluka;
        $user_working_vil=$data_output->user_village;

        if($utype=='1')
        {
        $data_user_output = User::where('users.user_district', $user_working_dist)
        ->select('id')
            ->get()
            ->toArray();
        }else if($utype=='2')
        {
            $data_user_output = User::where('users.user_taluka', $user_working_tal)
            ->select('id')
            ->get()
            ->toArray();
        }else if($utype=='3')
        {
            $data_user_output = User::where('users.user_village', $user_working_vil)
            ->select('id')
            ->get()
            ->toArray();
        }         

            $data_output = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
            ->leftJoin('users', 'tbl_gram_panchayat_documents.user_id', '=', 'users.id')
            ->leftJoin('tbl_area as district_labour', 'users.user_district', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
                ->whereIn('tbl_gram_panchayat_documents.user_id',$data_user_output)
                ->when($request->has('document_type_name'), function($query) use ($request) {
                    $query->where('tbl_documenttype.document_type_name', 'like', '%' . $request->document_type_name . '%');
                })
                ->when($request->get('user_taluka'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->user_taluka);
                })  
                ->when($request->get('user_village'), function($query) use ($request) {
                    $query->where('users.user_village', $request->user_village);
                })  
                ->when($request->get('from_date'), function($query) use ($fromDate, $toDate) {
                    $query->whereBetween('tbl_gram_panchayat_documents.updated_at', [$fromDate, $toDate]);
                })
                ->select(
                    'tbl_gram_panchayat_documents.id',
                    'tbl_gram_panchayat_documents.document_name',
                    'tbl_documenttype.document_type_name',
                    'tbl_gram_panchayat_documents.document_pdf',
                    'users.user_district',
                    'district_labour.name as district_name',
                    'users.user_taluka',
                    'taluka_labour.name as taluka_name',
                    'users.user_village',
                    'village_labour.name as village_name',
                    'tbl_gram_panchayat_documents.updated_at',
                )->get();
                foreach ($data_output as $document_data) {
                    $document_data->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document_data->document_pdf;
                }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document List Get Fail', 'error' => $e->getMessage()], 500);
        }
    }
    public function getDownloadDocument(Request $request){
        try {
            $user = Auth::user()->id;
            $document_pdffile = $request->input('document_name');
        
            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $user)
                ->first();
        
            $utype = $data_output->user_type;
            $user_working_dist = $data_output->user_district;
            $user_working_tal = $data_output->user_taluka;
            $user_working_vil = $data_output->user_village;
        
            if ($utype == '1') {
                $data_user_output = User::where('users.user_district', $user_working_dist)
                    ->select('id')
                    ->pluck('id')
                    ->toArray();
            } else if ($utype == '2') {
                $data_user_output = User::where('users.user_taluka', $user_working_tal)
                    ->select('id')
                    ->pluck('id')
                    ->toArray();
            } else if ($utype == '3') {
                $data_user_output = User::where('users.user_village', $user_working_vil)
                    ->select('id')
                    ->pluck('id')
                    ->toArray();
            }
        
            $document = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
                ->whereIn('tbl_gram_panchayat_documents.user_id', $data_user_output)
                ->where('tbl_gram_panchayat_documents.document_name', $document_pdffile)
                ->when($request->has('document_name'), function($query) use ($request) {
                    $query->where('tbl_gram_panchayat_documents.document_name', 'like', '%' . $request->document_name . '%');
                })
                ->first();
           
        // dd( $document);
            if (!$document) {
                return response()->json(['status' => 'false', 'message' => 'Document not found'], 404);
            }
           
                $document->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document->document_pdf;
            
            return response()->json(['status' => 'true', 'message' => 'Document retrieved successfully', 'data' => $document], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document retrieval failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function countGramsevakLabourDocument(Request $request) {
        try {
            $user = Auth::user();
    
            $documentCount = GramPanchayatDocuments::where('user_id', $user->id)
                ->count();
    
            return response()->json([
                'status' => 'true',
                'message' => 'Counts retrieved successfully',
                'document_count' => $documentCount,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'Error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
