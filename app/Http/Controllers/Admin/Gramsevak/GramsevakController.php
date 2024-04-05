<?php

namespace App\Http\Controllers\Admin\Gramsevak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Gramsevak\GramsevakServices;
use App\Models\ {
    Roles,
    Permissions,
    TblArea,
    User,
    Labour,
    LabourAttendanceMark,
    Registrationstatus,
    Reasons,
    HistoryModel,
    Project,
    HistoryDocumentModel
};
use Validator;
use session;
use Config;

class GramsevakController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->service = new GramsevakServices();
    }

    public function index()
    {

        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');
        
        $district_data = TblArea::where('parent_id', '2')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

        $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

                    $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                    ->where('users.id', $sess_user_id)
                    ->first();
    
                $utype=$data_output->user_type;
                $user_working_dist=$data_output->user_district;
                $user_working_tal=$data_output->user_taluka;
                $user_working_vil=$data_output->user_village;
    
    
                    if($utype=='1')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.district',$user_working_dist)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='2')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.taluka',$user_working_tal)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='3')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.village',$user_working_vil)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                } 
                
        $gramsevaks = $this->service->index();
        return view('admin.pages.gramsevak.list-gramsevaks',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocuments(Request $request)
    {
        try {

            $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
            ->where('id', '!=', 1)
            ->select('id','status_name')
            ->get()
            ->toArray();

            $dynamic_reasons = Reasons::where('is_active', 1)
            ->select('id','reason_name')
            ->get()
            ->toArray();

            $data_gram_doc_details = $this->service->showGramsevakDocuments($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function updateGramDocumentStatus(Request $request){
        // $rules = [
        //     'is_approved' => 'required',
        //  ];       

        // $messages = [   
        //                 'is_approved.required' => 'Please enter email.',
        //                 // 'email.email' => 'Please enter valid email.',
        //                 // 'u_uname.required' => 'Please enter user uname.',
        //                 // 'password.required' => 'Please enter password.',
        //             ];


        try {
            
                $register_user = $this->service->updateGramDocumentStatus($request);
                if($register_user)
                {

                    $sess_user_id=session()->get('user_id');
                    $sess_user_type=session()->get('user_type');
                    $sess_user_role=session()->get('role_id');

                if($request->is_approved=='3' && $request['other_remark']!='')
                {    
                $history = new HistoryDocumentModel();
                $history->user_id = $sess_user_id; 
                $history->roles_id = $sess_user_role; 
                $history->gram_document_id = $request->edit_id;
                $history->is_approved = $request->is_approved;
                $history->reason_doc_id = $request->reason_id; 
                $history->other_remark = $request->other_remark; 
                $history->save();
                }else if($request->is_approved=='3' && $request['other_remark']=='')
                {    
                    // Create a history record
                $history = new HistoryDocumentModel();
                $history->user_id = $sess_user_id; 
                $history->roles_id = $sess_user_role; 
                $history->gram_document_id = $request->edit_id;
                $history->is_approved = $request->is_approved;
                $history->reason_doc_id = $request->reason_id; 
                $history->save();
                }
    
                $showId=$request->show_id;
                // dd($showId);
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        // $user_doc_data = GramPanchayatDocuments::leftJoin('documenttype', 'documenttype.id', '=', 'tbl_gram_panchayat_documents.document_type_id')
                        // ->where('tbl_gram_panchayat_documents.user_id', $showId)
                        //     ->select('tbl_gram_panchayat_documents.id',
                        //     'tbl_gram_panchayat_documents.user_id',
                        //     'tbl_gram_panchayat_documents.document_type_id',
                        //     'tbl_gram_panchayat_documents.document_name',
                        //     'tbl_gram_panchayat_documents.document_pdf',
                        //     'tbl_gram_panchayat_documents.is_active',
                        //     'documenttype.document_type_name',
                        //     'tbl_gram_panchayat_documents.is_approved',
                        //     'tbl_gram_panchayat_documents.is_resubmitted',
                        //     'tbl_gram_panchayat_documents.reason_id',
                        //     'tbl_gram_panchayat_documents.other_remark',
                        //     )
                        //     ->get();
                        //     return response()->json(['labour_attendance_ajax_data' => $user_doc_data]);

                        return true;
                    }
                    else {
                        return false;
                    }
                }
                
            // }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }


  
   

   
}