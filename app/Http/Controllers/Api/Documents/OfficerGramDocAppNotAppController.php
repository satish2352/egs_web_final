<?php

namespace App\Http\Controllers\Api\Documents;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
	Documenttype,
    GramPanchayatDocuments
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class OfficerGramDocAppNotAppController extends Controller
{
        public function getReceivedDocumentListForAppNotApp(Request $request){
            try {
                $user = Auth::user()->id;
                $is_approved = '' ;

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

                $data_user_output = User::select('id');
                if($utype=='1')
                {
                    $data_user_output = $data_user_output->where('users.user_district', $user_working_dist);
                } else if($utype=='2')
                {
                    $data_user_output = $data_user_output->where('users.user_taluka', $user_working_tal);
                } else if($utype=='3')
                {
                    $data_user_output = $data_user_output->where('users.user_village', $user_working_vil);
                }

                $data_user_output = $data_user_output->get()->toArray();    
                


                if($request->has('is_approved') && $request->is_approved == 'received') {  //1
                    $is_approved = 1 ;
                } elseif($request->has('is_approved') && $request->is_approved == 'not_approved') { //3
                    $is_approved = 3 ;
                } elseif($request->has('is_approved') && $request->is_approved == 'approved') { //3
                    $is_approved = 2 ;
                } 

                    $data_output = GramPanchayatDocuments::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
                ->leftJoin('users', 'tbl_gram_panchayat_documents.user_id', '=', 'users.id')
                ->leftJoin('tbl_area as district_u', 'users.user_district', '=', 'district_u.location_id')
                ->leftJoin('tbl_area as taluka_u', 'users.user_taluka', '=', 'taluka_u.location_id')
                ->leftJoin('tbl_area as village_u', 'users.user_village', '=', 'village_u.location_id')
                    ->where('tbl_gram_panchayat_documents.user_id', $data_user_output)
                    ->when($request->has('document_type_name'), function($query) use ($request) {
                        $query->where('tbl_documenttype.document_type_name', 'like', '%' . $request->document_type_name . '%');
                    })
                    ->when($request->has('gram_document_id'), function($query) use ($request) {
                        $query->where('tbl_gram_panchayat_documents.id',$request->gram_document_id);
                    })
                    ->when($request->has('is_approved'), function($query) use ($is_approved) {
                        $query->where('tbl_gram_panchayat_documents.is_approved', $is_approved);
                    })
                    ->when($request->get('from_date'), function($query) use ($fromDate, $toDate) {
                        $query->whereBetween('tbl_gram_panchayat_documents.updated_at', [$fromDate, $toDate]);
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
                return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'Document List Get Fail', 'error' => $e->getMessage()], 500);
            }
       }
        public function updateDocumentStatusApproved(Request $request){
            try {
                $user = Auth::user()->id;
                    // Validate the incoming request
                $validator = Validator::make($request->all(), [
                    'gram_document_id' => 'required',
                ]);
        
                if ($validator->fails()) {
                    return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
                }
                
            
                $updated = GramPanchayatDocuments::where('id', $request->gram_document_id)
                    ->where('is_approved', 1)
                    ->update(['is_approved' => 2,'is_resubmitted'=> 0]); 
                    
        
                if ($updated) {
                    return response()->json(['status' => 'true', 'message' => 'Document status updated successfully'], 200);
                } else {
                    return response()->json(['status' => 'false', 'message' => 'No document found with the provided Id'], 200);
                }
        
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'Update failed','error' => $e->getMessage()], 500);
            }
        }
        public function updateDocumentStatusNotApproved(Request $request) {
            try {
                $user = Auth::user();
                
                $gram_document_id = $request->input('gram_document_id'); 
            
            
                $validator = Validator::make($request->all(), [
                    'gram_document_id' => 'required',
                    'doc_reason_id' => 'required',
                    'is_approved' => 'required',
                ]);
        
                if ($validator->fails()) {
                    return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
                }
        
                // Update labor entry
                $updateData = [
                    'is_approved' => 3,
                    'doc_reason_id' => $request->doc_reason_id, 
                ];
        
                // Include 'other_remark' in the update data if it's provided
                if ($request->has('other_remark')) {
                    $updateData['other_remark'] = $request->other_remark ?: ''; // Set to empty string if not provided
                }
        
                $updated = GramPanchayatDocuments::where('id', $request->gram_document_id)
                    ->where('is_approved', 1)
                    ->update($updateData);
                if ($updated) {
                    // Create a history record
                    $history = new HistoryModel();
                    $history->user_id = $user->id; 
                    $history->roles_id = $user->role_id; 
                    $history->gram_document_id = $request->gram_document_id;
                    $history->is_approved = $request->is_approved;
                    $history->reason_id = $request->reason_id; 
                    
                    if ($request->has('other_remark')) {
                        $history->other_remark = $request->other_remark ?: 'null'; // Set to empty string if not provided
                    }
        
                    $history->save();
        
                    return response()->json(['status' => 'true', 'message' => 'Document status updated successfully'], 200);
                } else {
                    return response()->json(['status' => 'false', 'message' => 'No document found with the provided Document Id or status is not approved'], 200);
                }
        
            } catch (\Exception $e) {
                return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
            }
        }

}
