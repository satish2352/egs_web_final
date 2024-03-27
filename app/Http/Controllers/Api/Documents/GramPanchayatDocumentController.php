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
                ->when($request->has('document_type_name'), function($query) use ($request) {
                    $query->where('tbl_documenttype.document_type_name', 'like', '%' . $request->document_type_name . '%');
                })
                ->select(
                    'tbl_gram_panchayat_documents.id',
                    'tbl_gram_panchayat_documents.document_name',
                    'tbl_documenttype.document_type_name',
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
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
            }          
            $document_data = GramPanchayatDocuments::where('id', $request->id)
            ->first();
    
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
            $document_data->document_name = $request->document_name;
            $document_data->document_pdf = $new_pdf_name;
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

    // public function getDownloadDocument(Request $request){
    // try {
    //     $user = Auth::user()->id;
    //     $document_pdffile = $request->input('document_pdf');
    
    //     $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
    //         ->where('users.id', $user)
    //         ->first();
    
    //     $utype = $data_output->user_type;
    //     $user_working_dist = $data_output->user_district;
    //     $user_working_tal = $data_output->user_taluka;
    //     $user_working_vil = $data_output->user_village;
    
    //     if ($utype == '1') {
    //         $data_user_output = User::where('users.user_district', $user_working_dist)
    //             ->select('id')
    //             ->get()
    //             ->toArray();
    //     } else if ($utype == '2') {
    //         $data_user_output = User::where('users.user_taluka', $user_working_tal)
    //             ->select('id')
    //             ->get()
    //             ->toArray();
    //     } else if ($utype == '3') {
    //         $data_user_output = User::where('users.user_village', $user_working_vil)
    //             ->select('id')
    //             ->get()
    //             ->toArray();
    //     }
    
    //     $query = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
    //         // ->whereIn('tbl_gram_panchayat_documents.user_id', $data_user_output)
    //         ->where('tbl_gram_panchayat_documents.document_pdf', $document_pdffile);
    
    //     $document = $query->first(); // Execute the query and get the first result
    
        
    
    //     // Construct absolute file path
    //     $file_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document->document_pdf;
    
        
    //     return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $ $file_path], 200);
    // } catch (\Exception $e) {
    //     return response()->json(['status' => 'false', 'message' => 'Document List Get Fail', 'error' => $e->getMessage()], 500);
    // }
        
    // }


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
        
            $data_output = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
            ->leftJoin('users', 'tbl_gram_panchayat_documents.user_id', '=', 'users.id')
            // ->leftJoin('tbl_area as district_labour', 'users.user_district', '=', 'district_labour.location_id')
            //     ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
            //     ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
                ->whereIn('tbl_gram_panchayat_documents.user_id',$data_user_output)
                ->when($request->has('document_name'), function($query) use ($request) {
                    $query->where('tbl_gram_panchayat_documents.document_name', 'like', '%' . $request->document_name . '%');
                })
               
               
                ->select(
                    'tbl_gram_panchayat_documents.id',
                    'tbl_gram_panchayat_documents.document_name',
                    'tbl_documenttype.document_type_name',
                    'tbl_gram_panchayat_documents.document_pdf',
                    // 'users.user_district',
                    // 'district_labour.name as district_name',
                    // 'users.user_taluka',
                    // 'taluka_labour.name as taluka_name',
                    // 'users.user_village',
                    // 'village_labour.name as village_name',
                    'tbl_gram_panchayat_documents.updated_at',
                )->get();
                foreach ($data_output as $document_data) {
                    $document_data->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document_data->document_pdf;
                }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
            // $query = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
            //     ->whereIn('tbl_gram_panchayat_documents.user_id', $data_user_output)
            //     ->where('tbl_gram_panchayat_documents.document_pdf', $document_pdffile)
            //     ->when($request->has('document_name'), function($query) use ($request) {
            //         $query->where('tbl_documenttype.document_name', 'like', '%' . $request->document_name . '%');
            //     });
        
            // $document = $query->first(); // Execute the query and get the first result
        
            // if (!$document) {
            //     return response()->json(['status' => 'false', 'message' => 'Document not found'], 404);
            // }
        
            // // Construct absolute file path
            // $file_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document->document_pdf;
     
            // return response()->json(['status' => 'true', 'message' => 'Document retrieved successfully', 'data' => $file_path], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document retrieval failed', 'error' => $e->getMessage()], 500);
        }
    }
    
//     public function getDownloadDocument(Request $request)
//      {
//         // try {
//             $user = Auth::user()->id;
            
//             $document_pdffile = $request->input('document_pdf');
            
// // dd($document_pdf);
//             $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
//             ->where('users.id', $user)
//             ->first();

//         $utype=$data_output->user_type;
//         $user_working_dist=$data_output->user_district;
//         $user_working_tal=$data_output->user_taluka;
//         $user_working_vil=$data_output->user_village;

//         if($utype=='1')
//         {
//         $data_user_output = User::where('users.user_district', $user_working_dist)
//         ->select('id')
//             ->get()
//             ->toArray();
//         }else if($utype=='2')
//         {
//             $data_user_output = User::where('users.user_taluka', $user_working_tal)
//             ->select('id')
//             ->get()
//             ->toArray();
//         }else if($utype=='3')
//         {
//             $data_user_output = User::where('users.user_village', $user_working_vil)
//             ->select('id')
//             ->get()
//             ->toArray();
//         }     
           
//     $query = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
//     // ->whereIn('tbl_gram_panchayat_documents.user_id', $data_user_output)
//     ->where('tbl_gram_panchayat_documents.document_pdf', $document_pdffile);

// $document = $query->first(); // Execute the query and get the first result


// // Check if the document exists
// if (!$document) {
//     return response()->json(['status' => 'false', 'message' => 'Document not found'], 200);
// }

// // // Construct absolute file path
//  $file_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document->document_pdf;
 
// $file_path11 = rtrim($file_path, '\\/');

// if (file_exists($file_path11)) {
    
//     header('Content-Type: application/pdf');
   
//     readfile($file_path11);
//     exit;
// } else {
   
//     $response = [
//         "status" => "false",
//         "message" => "Document Download Failed",
//          "error" => "$file_path11"
//     ];
//     echo json_encode($response);
// }

//      }






// } catch (\Exception $e) {
// return response()->json(['status' => 'false', 'message' => 'Document Download Failed', 'error' => $e->getMessage()], 500);
// }
// }


    //         $document = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
    //         // ->where('tbl_gram_panchayat_documents.user_id', $user)
    //         ->whereIn('tbl_gram_panchayat_documents.user_id',$data_user_output)
    //         ->where('tbl_gram_panchayat_documents.document_pdf', $document_pdf);
    //         // ->when($request->has('documenttype'), function($query) use ($request) {
    //         //     $query->where('tbl_documenttype.documenttype', 'like', '%' . $request->documenttype . '%');
    //         // })
            
            
    //         // whereIn('user_id', $data_user_output)
            
    //         // ->first();

    //                                         //   dd($document);
    //         // if (!$document) {
    //         //     return response()->json(['status' => 'false', 'message' => 'Document not found'], 200);
    //         // }
    
    //         // Construct absolute file path
    //          $filePath = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document->document_pdf;
    //     //    $filePath = 'http://localhost/egs_web_final/storage/all_web_data/documents/GramPanchayatDoc/4_513323_document.pdf';
    //        dd($filePath);
    //        $headers = array(
    //             'Content-Type: application/pdf',
    //           );
  
    //           return response()->download(rtrim($filePath,"\\"), 'filename.pdf', $headers);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Document Download Failed', 'error' => $e->getMessage()], 500);
    //     }
    // }
    
}
