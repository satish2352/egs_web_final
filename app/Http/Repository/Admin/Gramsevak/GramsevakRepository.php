<?php
namespace App\Http\Repository\Admin\Gramsevak;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\{
	User,
	Permissions,
	RolesPermissions,
	Roles,
	Labour,
	LabourAttendanceMark,
	LabourFamilyDetails,
	GramPanchayatDocuments,
	HistoryModel,
	Skills
};
use Illuminate\Support\Facades\Mail;

class GramsevakRepository
{

	public function getGramsevakList() {

		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village')
				->get();

		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		return $data_users;
	}

	public function showGramsevakDocuments($id)
	{
		$data_gram_doc=[];
		try {
			$data_gram_doc['user_data'] = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.id', $id)
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village')
				->first();

			$data_gram_doc['user_doc_data'] = GramPanchayatDocuments::leftJoin('documenttype', 'documenttype.id', '=', 'tbl_gram_panchayat_documents.document_type_id')
			->where('tbl_gram_panchayat_documents.user_id', $id)
				->select('tbl_gram_panchayat_documents.id',
				'tbl_gram_panchayat_documents.user_id',
				'tbl_gram_panchayat_documents.document_type_id',
				'tbl_gram_panchayat_documents.document_name',
				'tbl_gram_panchayat_documents.document_pdf',
				'tbl_gram_panchayat_documents.is_active',
				'documenttype.document_type_name',
				'tbl_gram_panchayat_documents.is_approved',
				'tbl_gram_panchayat_documents.is_resubmitted',
				'tbl_gram_panchayat_documents.reason_doc_id',
				'tbl_gram_panchayat_documents.other_remark',
				)
				->get();
					

	
			if ($data_gram_doc) {
				return $data_gram_doc;
			} else {
				return null;
			}
		} catch (\Exception $e) {
			return [
				'msg' => $e->getMessage(),
				'status' => 'error'
			];
		}
	}

	public function updateGramDocumentStatus($request)
	{
		// dd($request);
		if($request['is_approved']=='2')
		{
			$user_data = GramPanchayatDocuments::where('id',$request['edit_id']) 
						->update([
							'is_approved' => $request['is_approved'],
							'is_resubmitted' => '0',
						]);
		}else if($request['is_approved']=='3' && $request['other_remark']!='')		
		{
			$user_data = GramPanchayatDocuments::where('id',$request['edit_id']) 
						->update([
							'is_approved' => $request['is_approved'],
							'reason_doc_id' => $request['reason_doc_id'],
							'other_remark' => $request['other_remark']
						]);
		}
		else if($request['is_approved']=='3' && $request['other_remark']=='')		
		{
			$user_data = GramPanchayatDocuments::where('id',$request['edit_id']) 
						->update([
							'is_approved' => $request['is_approved'],
							'reason_doc_id' => $request['reason_doc_id']
						]);
		}			
		// dd($user_data);
		// $this->updateRolesPermissions($request, $request->edit_id);
		return $request->edit_id;
	}

	public function ListGrampanchayatDocuments()
	{
		$data_gram_doc=[];
		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		try {
			// $data_gram_doc['user_data'] = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
			// 	->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
			// 	->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
			// 	->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
			// 	->where('users.id', $id)
			// 	->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
			// 	'users.address','users.pincode','users.user_profile','roles.role_name',
			// 	'district_user.name as district','taluka_user.name as taluka','village_user.name as village')
			// 	->first();

			$user_doc_data= GramPanchayatDocuments::leftJoin('documenttype', 'documenttype.id', '=', 'tbl_gram_panchayat_documents.document_type_id')
				->leftJoin('registrationstatus', 'tbl_gram_panchayat_documents.is_approved', '=', 'registrationstatus.id')
				->leftJoin('tbl_doc_reason', function($join) {
					$join->on('tbl_gram_panchayat_documents.reason_doc_id', '=', 'tbl_doc_reason.id')
						 ->where(function($query) {
							 $query->where('tbl_gram_panchayat_documents.reason_doc_id', '>', 0)
								   ->orWhereNull('tbl_gram_panchayat_documents.reason_doc_id');
						 });
						})		 
				->where('tbl_gram_panchayat_documents.user_id', $sess_user_id)
				->select('tbl_gram_panchayat_documents.id',
				'tbl_gram_panchayat_documents.user_id',
				'tbl_gram_panchayat_documents.document_type_id',
				'tbl_gram_panchayat_documents.document_name',
				'tbl_gram_panchayat_documents.document_pdf',
				'tbl_gram_panchayat_documents.is_active',
				'documenttype.document_type_name',
				'tbl_gram_panchayat_documents.is_approved',
				'tbl_gram_panchayat_documents.is_resubmitted',
				'tbl_gram_panchayat_documents.reason_doc_id',
				'tbl_gram_panchayat_documents.other_remark',
				'registrationstatus.status_name',
				'tbl_doc_reason.reason_name',
				)
				->get();

	
			if ($user_doc_data) {
				return $user_doc_data;
			} else {
				return null;
			}
		} catch (\Exception $e) {
			return [
				'msg' => $e->getMessage(),
				'status' => 'error'
			];
		}
	}

}