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
                // ->where('tbl_gram_panchayat_documents.user_id', $user)
                ->whereIn('tbl_gram_panchayat_documents.user_id',$data_user_output)
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

    public function getDownloadDocument(Request $request)
    {
        $user = Auth::user()->id;
        $document_pdffile = $request->input('document_pdf');
    
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
                ->get()
                ->toArray();
        } else if ($utype == '2') {
            $data_user_output = User::where('users.user_taluka', $user_working_tal)
                ->select('id')
                ->get()
                ->toArray();
        } else if ($utype == '3') {
            $data_user_output = User::where('users.user_village', $user_working_vil)
                ->select('id')
                ->get()
                ->toArray();
        }
    
        $query = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
            // ->whereIn('tbl_gram_panchayat_documents.user_id', $data_user_output)
            ->where('tbl_gram_panchayat_documents.document_pdf', $document_pdffile);
    
        $document = $query->first(); // Execute the query and get the first result
    
        // Check if the document exists
        if (!$document) {
            return response()->json(['status' => 'false', 'message' => 'Document not found'], 200);
        }
    
        // Construct absolute file path
        $file_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document->document_pdf;
    
        $file_path11 = rtrim($file_path, '\\/');
    dd($file_path11);
        if (file_exists($file_path11)) {
            // Download the file
            return response()->download($file_path11);
        } else {
            $response = [
                "status" => "false",
                "message" => "Document Download Failed",
                "error" => "$file_path11"
            ];
            return response()->json($response);
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
