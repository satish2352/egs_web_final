<?php
namespace App\Http\Repository\Admin\Labours;

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
	LabourAttendanceMark
};
use Illuminate\Support\Facades\Mail;

class LabourRepository
{

	public function getLaboursList() {
		$sess_user_id=session()->get('user_id');
		if($sess_user_id=='1')
		{
     	$data_labours = Labour::leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
		->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
		->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
		->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
          ->select(
			'labour.id',
			'labour.full_name',
			'labour.date_of_birth',
			'gender_labour.gender_name as gender_name',
			'district_labour.name as district_id',
			'taluka_labour.name as taluka_id',
			'village_labour.name as village_id',
			'labour.mobile_number',
			'labour.landline_number',
			'labour.mgnrega_card_id',
			'labour.aadhar_image',
			'labour.mgnrega_image', 
			'labour.profile_image', 
			'labour.voter_image', 
			'labour.is_active',
          )->get();
		  }else if($sess_user_id!='1')
		  {
			$data_labours = Labour::leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
		->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
		->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
		->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
		->where('labour.user_id', $sess_user_id)
          ->select(
			'labour.id',
			'labour.full_name',
			'labour.date_of_birth',
			'gender_labour.gender_name as gender_name',
			'district_labour.name as district_id',
			'taluka_labour.name as taluka_id',
			'village_labour.name as village_id',
			'labour.mobile_number',
			'labour.landline_number',
			'labour.mgnrega_card_id',
			'labour.aadhar_image',
			'labour.mgnrega_image', 
			'labour.profile_image', 
			'labour.voter_image', 
			'labour.is_active',
          )->get();

			}		
		return $data_labours;
	}

	public function getLabourAttendanceList() {
		$sess_user_id=session()->get('user_id');
// dd($sess_user_id);
		if($sess_user_id=='1')
		{
     	
		  $data_labour_attendance = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
            ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                ->select(
                    'tbl_mark_attendance.id',
                    'projects.project_name',
                    'labour.full_name as full_name',
                    'labour.date_of_birth',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'tbl_mark_attendance.attendance_day'
                )->get();
				// dd($data_labour_attendance);
		  }else if($sess_user_id!='1')
		  {
			$data_labour_attendance = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
            ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
			->leftJoin('project_users', 'project_users.project_id', '=', 'projects.id')
                ->where('tbl_mark_attendance.user_id', $sess_user_id)
                ->where('labour.user_id', $sess_user_id)
                ->where('project_users.user_id', $sess_user_id)
                ->select(
                    'tbl_mark_attendance.id',
                    'projects.project_name',
                    'labour.full_name as full_name',
                    'labour.date_of_birth',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'tbl_mark_attendance.attendance_day'
                )->get();

			}	
			// dd();	
		return $data_labour_attendance;
	}

	


	public function permissionsData()
	{
		$permissions = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		return $permissions;
	}
	// public function register($request)
	// {
	// 	$ipAddress = getIPAddress($request);
	// 	$user_data = new User();
	// 	$user_data->email = $request['email'];
	// 	// $user_data->u_uname = $request['u_uname'];
	// 	$user_data->password = bcrypt($request['password']);
	// 	$user_data->role_id = $request['role_id'];
	// 	$user_data->f_name = $request['f_name'];
	// 	$user_data->m_name = $request['m_name'];
	// 	$user_data->l_name = $request['l_name'];
	// 	$user_data->number = $request['number'];
	// 	$user_data->designation = $request['designation'];
	// 	$user_data->address = $request['address'];
	// 	$user_data->state = $request['state'];
	// 	$user_data->city = $request['city'];
	// 	$user_data->pincode = $request['pincode'];
	// 	$user_data->ip_address = $ipAddress;
	// 	$user_data->is_active = isset($request['is_active']) ? true : false;
	// 	$user_data->save();

	// 	$last_insert_id = $user_data->id;
	// 	// $this->insertRolesPermissions($request, $last_insert_id);
	// 	return $last_insert_id;
	// }

	public function addProject($request)
	{
		try {
		$data =array();
		// $ipAddress = getIPAddress($request);
		$project_data = new Projects();
		// $project_data->email = $request['email'];
		// $project_data->u_uname = $request['u_uname'];
		// $project_data->password = bcrypt($request['password']);
		$project_data->project_name = $request['project_name'];
		$project_data->state = $request['state'];
		$project_data->district = $request['district'];
		$project_data->taluka	 = $request['taluka'];
		$project_data->village = $request['village'];

		$project_data->latitude = $request['latitude'];
		$project_data->longitude = $request['longitude'];
		$project_data->start_date = $request['start_date'];
		$project_data->end_date = $request['end_date'];
		$project_data->description = $request['description'];
		$project_data->is_active = isset($request['is_active']) ? true : false;
		$project_data->save();

		$last_insert_id = $project_data->id;
		// $this->insertRolesPermissions($request, $last_insert_id);

		// $imageProfile = $last_insert_id .'_' . rand(100000, 999999) . '_english.' . $request->user_profile->extension();
        
        // $user_detail = User::find($last_insert_id); // Assuming $request directly contains the ID
        // $user_detail->user_profile = $imageProfile; // Save the image filename to the database
        // $user_detail->save();
        // echo  $user_detail;
		// die();
        // $data['imageProfile'] =$imageProfile;
        return $project_data;
	} catch (\Exception $e) {
		return [
			'msg' => $e,
			'status' => 'error'
		];
	}

	}

	public function update($request)
	{
        $ipAddress = getIPAddress($request);
		$user_data = User::where('id',$request['edit_id']) 
						->update([
							// 'u_uname' => $request['u_uname'],
							'role_id' => $request['role_id'],
							'f_name' => $request['f_name'],
							'm_name' => $request['m_name'],
							'l_name' => $request['l_name'],
							'number' => $request['number'],
							'imei_no' => $request['imei_no'],
							'aadhar_no' => $request['aadhar_no'],
							'address' => $request['address'],
							'state' => $request['state'],
							'district' => $request['district'],
							'taluka' => $request['taluka'],
							'village' => $request['village'],
							'pincode' => $request['pincode'],
							'is_active' => isset($request['is_active']) ? true :false,
						]);
		
		// $this->updateRolesPermissions($request, $request->edit_id);
		return $request->edit_id;
	}


	// private function updateRolesPermissions($request, $last_insert_id) {

	// 	$permissions_data_from_table  = $this->permissionsData();
	// 	$update_data = array();
	// 	foreach ($permissions_data_from_table as $key => $data) {
	// 		$permission_id  = 'permission_id_'.$data['id'];
	// 		$per_add  = 'per_add_'.$data['id'];
	// 		$per_update  = 'per_update_'.$data['id'];
	// 		$per_delete  = 'per_delete_'.$data['id'];

	// 		$update_data['role_id'] = $request->role_id;
	// 		if($request->has($per_add)) {
	// 			$update_data['per_add']  = true;
	// 		} else {
	// 			$update_data['per_add']  = false;
	// 		}

	// 		if($request->has($per_update)) {
	// 			$update_data['per_update']  = true;
	// 		} else {
	// 			$update_data['per_update']  = false;
	// 		}

	// 		if($request->has($per_delete)) {
	// 			$update_data['per_delete']  = true;
	// 		} else {
	// 			$update_data['per_delete']  = false;
	// 		}

	// 		$permissions_data_all = RolesPermissions::where([
	// 			'user_id' =>$request['edit_id'],
	// 			'permission_id' =>$data['id']
	// 		])->get()->toArray();
	// 		if(count($permissions_data_all)>0) {

	// 			$permissions_data = RolesPermissions::where([
	// 				'user_id' =>$request['edit_id'],
	// 				'permission_id' =>$data['id']
	// 			])->update($update_data);
	// 		} else {
	// 			$update_data['user_id']  = $request['edit_id'];
	// 			$update_data['permission_id']  = $data['id'];
	// 			$permissions_data = RolesPermissions::insert($update_data);
	// 		}

	// 	}
	// 	return "ok";
	// }


	// private function insertRolesPermissions($request, $last_insert_id) {

	// 	$permissions_data_from_table  = $this->permissionsData();
	// 	foreach ($permissions_data_from_table as $key => $data) {
	// 		$permission_id  = 'permission_id_'.$data['id'];
	// 		$per_add  = 'per_add_'.$data['id'];
	// 		$per_update  = 'per_update_'.$data['id'];
	// 		$per_delete  = 'per_delete_'.$data['id'];
	// 		if($request->has($permission_id) && ($request->has($per_add) || $request->has($per_update) || $request->has($per_delete))) {
	// 			// dd("I am here for permission");
	// 			$permissions_data = new RolesPermissions();
	// 			$permissions_data->permission_id = $data['id'];
	// 			$permissions_data->role_id = $request->role_id;
	// 			$permissions_data->user_id = $last_insert_id;

	// 			if($request->has($per_add)) {
	// 				$permissions_data->per_add  = true;
	// 			}

	// 			if($request->has($per_update)) {
	// 				$permissions_data->per_update  = true;
	// 			}

	// 			if($request->has($per_delete)) {
	// 				$permissions_data->per_delete  = true;
	// 			}
	// 			$permissions_data->save();
	// 		}

	// 	}
	// 	return "ok";
	// }

	public function checkDupCredentials($request)
	{
		return User::where('email', '=', $request['email'])
			// ->orWhere('u_uname','=',$request['u_uname'])
			->select('id')->get();
	}

	public function editProjects($reuest)
	{

		$data_projects = [];

		// $data_users['roles'] = Roles::where('is_active', true)
		// 	->select('id', 'role_name')
		// 	->get()
		// 	->toArray();
		$data_projects['permissions'] = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		$data_projects_data = Projects::where('projects.id', '=', base64_decode($reuest->edit_id))
			// ->where('roles_permissions.is_active','=',true)
			// ->where('users.is_active','=',true)
			->select(
				// 'roles.id as role_id',
				'projects.project_name',
				'projects.description',
				'projects.state',
				'projects.district',
				'projects.village',
				'projects.start_date',
				'projects.end_date',
				'projects.latitude',
				'projects.longitude',
				'projects.id',
				'projects.is_active',
			)->get()
			->toArray();
						
		$data_projects['data_projects'] = $data_projects_data[0];
		// $data_users['permissions_user'] = User::join('roles_permissions', function($join) {
		// 					$join->on('users.id', '=', 'roles_permissions.user_id');
		// 				})
		// 				->join('permissions', function($join) {
		// 					$join->on('roles_permissions.permission_id', '=', 'permissions.id');
		// 				})
		// 				->where('roles_permissions.user_id','=',$reuest->edit_id)
		// 				->where('roles_permissions.is_active','=',true)
		// 				// ->where('users.is_active','=',true)
		// 				->select(
		// 					'roles_permissions.per_add',
		// 					'roles_permissions.per_update',
		// 					'roles_permissions.per_delete',
		// 					'permissions.id as permissions_id'
		// 					)->get()
		// 					->toArray();

		return $data_projects;
	}

	// public function delete($request)
	// {
	// 	$user = User::where(['id' => $request->delete_id])
	// 		->update(['is_active' => false]);
	// 	// $rolesPermissions = RolesPermissions::where(['user_id' => $request->delete_id])
	// 	// 	->update(['is_active' => false]);

	// 	return "ok";
	// }

	public function delete($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
              
                $user->delete();
               
                return $user;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function getById($id)
	{
		// dd($id);
		try {
			$data_users = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
			->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
			  ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
			  ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
				->where('labour.id', $id)
				->select('labour.id',
				'labour.full_name',
				'labour.date_of_birth',
				'gender_labour.gender_name as gender_name',
				'district_labour.name as district_id',
				'taluka_labour.name as taluka_id',
				'village_labour.name as village_id',
				'labour.mobile_number',
				'labour.landline_number',
				'labour.mgnrega_card_id',
				'labour.aadhar_image',
				'labour.mgnrega_image', 
				'labour.profile_image', 
				'labour.voter_image',
				'labour.latitude',
				'labour.longitude',
				'labour.is_active',)
				->first();
	
			if ($data_users) {
				return $data_users;
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

	public function updateOne($request){
        try {
            $project = Labour::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the userr model
            if ($project) {
                $is_active = $project->is_active === 1 ? 0 : 1;
                $project->is_active = $is_active;
                $project->save();

                return [
                    'msg' => 'Labour updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Labour not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Labour.',
                'status' => 'error'
            ];
        }
    }

	public function getProfile()
	{
		$user_detail = User::where('is_active', true)
			->where('id', session()->get('user_id'))
			->select('id', 'f_name', 'm_name', 'l_name', 'email', 'password', 'number', 'designation','user_profile')
			->first();
		return $user_detail;
	}


	public function updateProfile($request)
	{
		try {
			
			$return_data = array();
			$otp = rand(6, 999999);

			
			$update_data = [
				'f_name' => $request->f_name,
				'm_name' => $request->m_name,
				'l_name' => $request->l_name,
				'designation' => $request->designation,
			];
			
			if (isset($return_data['user_profile'])) {
				$previousUserProfile = $update_data->user_profile;
			}
			// if ($request->hasFile('user_profile')) {
			// 	$profileImage = $request->file('user_profile');
			// 	$newImagePathOrFilename = $profileImage->store('profile_images');
			// 	$update_data['user_profile'] = $newImagePathOrFilename;
			// }

			if (($request->number != $request->old_number) && !isset($request->password)) {
				$this->sendOTPEMAIL($otp, $request);
				info("only mobile change");
				$return_data['password_change'] = 'no';
				$update_data['otp'] = $otp;
				$return_data['mobile_change'] = 'yes';
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['new_mobile_number'] = $request->number;
				$return_data['password_new'] = '';
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

			}

			if ((isset($request->password) && $request->password !== '') && ($request->number == $request->old_number)) {
				info("only password change");
				// $update_data['password'] = bcrypt($request->password);
				$return_data['password_change'] = 'yes';
				$return_data['mobile_change'] = 'no';
				$update_data['otp'] = $otp;
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['password_new'] = bcrypt($request->password);
				$return_data['new_mobile_number'] = '';
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

				$this->sendOTPEMAIL($otp, $request);
			}

			if ((isset($request->password) && $request->password !== '') && ($request->number != $request->old_number)) {
				info("only password and mobile number changed");
				$update_data['otp'] = $otp;
				$return_data['password_new'] = bcrypt($request->password);
				$return_data['password_change'] = 'yes';
				$return_data['mobile_change'] = 'yes';
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['new_mobile_number'] = $request->number;
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

				$this->sendOTPEMAIL($otp, $request);
			}
			
			User::where('id', $request->edit_user_id)->update($update_data);

			$user_data = User::find($request->edit_user_id);
			$previousUserProfile = $user_data->english_image;
			$last_insert_id = $user_data->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['user_profile'] = $previousUserProfile;
			return $return_data;


		} catch (\Exception $e) {
			info($e);
		}

		// return $update_data;
	}

	public function sendOTPEMAIL($otp, $request) {
		try {
			$email_data = [
				'otp' => $otp,
			];
			$toEmail = $request->email;
			$senderSubject = 'Disaster Management OTP ' . date('d-m-Y H:i:s');
			$fromEmail = env('MAIL_USERNAME');
			Mail::send('admin.email.emailotp', ['email_data' => $email_data], function ($message) use ($toEmail, $fromEmail, $senderSubject) {
				$message->to($toEmail)->subject($senderSubject);
				$message->from($fromEmail, 'Disaster Management OTP');
			});
			return 'ok';
		} catch (\Exception $e) {
			info($e);
		}
	}
	
	// public function checkEmailExists(Request $request) {
    //     $userEmail = $request->input('email');
      
    //     $user = User::where('email', $userEmail)->first();
      
    //     if ($user) {
    //       return response()->json([
    //         'success' => false,
    //         'message' => 'This Email already exists.',
    //       ]);
    //     } else {
    //       return response()->json([
    //         'success' => true,
    //         'message' => 'This Email does not exist.',
    //       ]);
    //     }
    // }
}