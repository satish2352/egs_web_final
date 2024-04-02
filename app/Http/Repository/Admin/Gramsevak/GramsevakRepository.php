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
				->select('users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village')
				->first();

			$data_gram_doc['user_doc_data'] = GramPanchayatDocuments::leftJoin('documenttype', 'documenttype.id', '=', 'tbl_gram_panchayat_documents.document_type_id')
			->where('tbl_gram_panchayat_documents.user_id', $id)
				->select('tbl_gram_panchayat_documents.user_id',
				'tbl_gram_panchayat_documents.document_type_id',
				'tbl_gram_panchayat_documents.document_name',
				'tbl_gram_panchayat_documents.document_pdf',
				'tbl_gram_panchayat_documents.is_active',
				'documenttype.document_type_name')
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

}