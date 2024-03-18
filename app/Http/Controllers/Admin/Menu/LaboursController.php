<?php

namespace App\Http\Controllers\Admin\Menu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Labours\LabourServices;
use App\Models\ {
    Roles,
    Permissions,
    TblArea,
    User,
    Labour
};
use Validator;
use session;
use Config;

class LaboursController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->service = new LabourServices();
    }

    public function index()
    {
        $labours = $this->service->index();
        // dd($projects);
        return view('admin.pages.labours.list-labour',compact('labours'));
    }

    // public function getProf()
    // {
    //     $register_user = $this->service->index();
    //     return view('admin.layout.master',compact('register_user'));
    // }



    public function addProjects(){
        // $roles = Roles::where('is_active', true)
        //                 ->select('id','role_name')
        //                 ->get()
        //                 ->toArray();
        $permissions = Permissions::where('is_active', true)
                            ->select('id','route_name','permission_name','url')
                            ->get()
                            ->toArray();
        $dynamic_state = TblArea::where('location_type', 1)
                            ->select('location_id','name')
                            ->get()
                            ->toArray();
    	// return view('admin.pages.users.add-users',compact('roles','permissions','dynamic_state'));
    	return view('admin.pages.projects.add-projects',compact('permissions','dynamic_state'));
    }

    public function getCities(Request $request)
    {
        $stateId = $request->input('stateId');

        $city = TblArea::where('location_type', 2) // 4 represents cities
                    ->where('parent_id', $stateId)
                    ->get(['location_id', 'name']);
              return response()->json(['city' => $city]);

    }

    public function getDistrict(Request $request)
    {
        $stateId = $request->input('stateId');

        $district = TblArea::where('location_type', 2) // 4 represents cities
                    ->where('parent_id', $stateId)
                    ->get(['location_id', 'name']);
              return response()->json(['district' => $district]);

    }

    public function getTaluka(Request $request)
    {
        $districtId = $request->input('districtId');

        $taluka = TblArea::where('location_type', 3) // 4 represents cities
                    ->where('parent_id', $districtId)
                    ->get(['location_id', 'name']);
              return response()->json(['taluka' => $taluka]);

    }

    public function getVillage(Request $request)
    {
        $talukaId = $request->input('talukaId');

        $village = TblArea::where('location_type', 4) // 4 represents cities
                    ->where('parent_id', $talukaId)
                    ->get(['location_id', 'name']);
              return response()->json(['village' => $village]);

    }

    public function getState(Request $request)
    {
        $stateId = $request->input('stateId');
        $state =  TblArea::where('location_type', 1)
                            // ->where('parent_id', $stateId)
                            ->select('location_id','name')
                            ->get()
                            ->toArray();
        return response()->json(['state' => $state]);

    }

    public function editProjects(Request $request){
        
        $project_data = $this->service->editProjects($request);
        return view('admin.pages.projects.edit-projects',compact('project_data'));
    }

    public function update(Request $request){
        // $user_data = $this->service->editUsers($request);
        // return view('admin.pages.users.users-list',compact('user_data'));

        $rules = [
            // 'email' => 'required',
            // 'u_uname' => 'required',
            // 'password' => 'required',
            'role_id' => 'required',
            'f_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'm_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'l_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'number' =>  'required|regex:/^[0-9]{10}$/',
            'imei_no' => 'required',
            'aadhar_no' => 'required',
            'address' => ['required','regex:/^(?![0-9\s]+$)[A-Za-z0-9\s\.,#\-\(\)\[\]\{\}]+$/','max:255'],
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'village' => 'required',
            'pincode' => 'required|regex:/^[0-9]{6}$/',
         ];       

        $messages = [   
                        // 'email.required' => 'Please enter email.',
                        // 'email.email' => 'Please enter valid email.',
                        // 'u_uname.required' => 'Please enter user uname.',
                        // 'password.required' => 'Please enter password.',
                        'role_id.required' => 'Please select role type.',
                        'f_name.required' => 'Please enter first name.',
                         'f_name.regex' => 'Please  enter text only.',
                        'f_name.max'   => 'Please  enter first name length upto 255 character only.',

                        'm_name.required' =>'Please enter middle name.',
                        'm_name.regex' => 'Please  enter text only.',
                        'm_name.max'   => 'Please  enter middle name length upto 255 character only.',

                        'l_name.required' => 'Please enter last name.',
                        'l_name.regex' => 'Please  enter text only.',
                        'l_name.max'   => 'Please  enter last name length upto 255 character only.',

                        'number.required' => 'Please enter number.',
                        'number.regex' => 'Please enter only numbers with 10-digit.',

                        'imei_no.required' =>'Please enter IMEI Number.',
                        'aadhar_no.required' =>'Please enter Aadhar Number.',
                        // 'designation.regex' => 'Please  enter text only.',
                        // 'designation.max'   => 'Please  enter designation length upto 255 character only.',

                        'address.required' => 'Please enter address.',
                        'address.regex' => 'Please enter right address.',
                        'address.max'   => 'Please  enter address length upto 255 character only.',


                        'state.required' => 'Please select state.',
                        'district.required' =>'Please select District.',
                        'taluka.required' =>'Please select Taluka.',
                        'village.required' =>'Please select Village.',
                        'pincode.required' => 'Please enter pincode.',
                        'pincode.regex' => 'Please enter a 6-digit pincode.',
                    ];


        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->update($request);

                if($register_user)
                {
                
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('list-users')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('list-users')->withInput()->with(compact('msg','status'));
                    }
                }
                
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function store(Request $request)
    {

        try {

            $rules = [
                'project_name' => 'required|unique:projects|regex:/^[a-zA-Z\s]+$/u|max:255',
                'state' => 'required',
                'district' => 'required',
                'taluka' => 'required',
                'village' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
            ];
            $messages = [
                'project_name.required' => 'Please  enter title.',
                // 'role_name.unique' => 'Your role type is already exist.',
                'project_name.regex' => 'Please  enter text only.',
                'project_name.max' => 'Please  enter text length upto 255 character only.',
                'project_name.unique' => 'Title already exist.',

                'state.required' => 'Please  enter state.',
                'district.required' => 'Please  enter district.',
                'taluka.required' => 'Please  enter taluka.',
                'village.required' => 'Please  enter village.',
                'latitude.required' => 'Please  enter latitude.',
                'longitude.required' => 'Please  enter longitude.',
                'start_date.required' => 'Please  enter start_date.',
                'end_date.required' => 'Please  enter end_date.',
                'description.required' => 'Please  enter description.',

            ];

            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect('add-projects')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_role = $this->service->addProject($request);
                if ($add_role) {
                    $msg = $add_role['msg'];
                    $status = $add_role['status'];
                    if ($status == 'success') {
                        return redirect('list-role')->with(compact('msg', 'status'));
                    } else {
                        return redirect('add-role')->withInput()->with(compact('msg', 'status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-role')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function add(Request $request){
// dd($request);
// die;
        $rules = [
                //    'email' => 'required|unique:users,email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z])+\.)+([a-zA-Z0-9]{2,4})+$/',
                //     'password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[a-zA-Z\d]).{8,}$/',
                //     'password_confirmation' => 'required|same:password',
                //     'role_id' => 'required',
                //     'f_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'm_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'l_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'number' =>  'required|regex:/^[0-9]{10}$/',
                //     'designation' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'address' => ['required','regex:/^(?![0-9\s]+$)[A-Za-z0-9\s\.,#\-\(\)\[\]\{\}]+$/','max:255'],
                //     'state' => 'required',
                //     'city' => 'required',
                //     'pincode' => 'required|regex:/^[0-9]{6}$/',
                //     'user_profile' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.USER_PROFILE_MAX_SIZE").'|dimensions:min_width=150,min_height=150,max_width=400,max_height=400|min:'.Config::get("AllFileValidation.USER_PROFILE_MIN_SIZE").'',

                 ];       

        $messages = [   
                    //     'email.required' => 'Please enter email.',
                    //     'email.unique' => 'Your email is already exist.',
                    //     'email.regex' => 'Enter valid email.',
                    //     // 'u_uname.required'=>'Please enter firstname.',
                    //     // 'u_uname.regex' => 'Please  enter text only.',
                    //     // 'u_uname.max'   => 'Please  enter firstname length upto 255 character only.',       
                    //     'password.required' => 'Please enter password.',
                    //     'password.regex' => 'Password should be more than 8 numbers with atleast 1 capital letter,1 small letter, 1 number and 1 special character.',
                    //     'password_confirmation.same' => 'The password confirmation does not match.',
                    //     // 'password.min' => 'Please combination of number character of 8 char.',
                    //     'role_id.required' => 'Please select role type.',
                    //     'f_name.required' => 'Please enter first name.',
                    //      'f_name.regex' => 'Please  enter text only.',
                    //     'f_name.max'   => 'Please  enter first name length upto 255 character only.',

                    //     'm_name.required' =>'Please enter middle name.',
                    //     'm_name.regex' => 'Please  enter text only.',
                    //     'm_name.max'   => 'Please  enter middle name length upto 255 character only.',

                    //     'l_name.required' => 'Please enter last name.',
                    //     'l_name.regex' => 'Please  enter text only.',
                    //     'l_name.max'   => 'Please  enter last name length upto 255 character only.',

                    //     'number.required' => 'Please enter number.',
                    //     'number.regex' => 'Please enter only numbers with 10-digit.',

                    //     'designation.required' =>'Please enter designation.',
                    //     'designation.regex' => 'Please  enter text only.',
                    //     'designation.max'   => 'Please  enter designation length upto 255 character only.',

                    //     'address.required' => 'Please enter address.',
                    //     'address.regex' => 'Please enter right address.',
                    //     'address.max'   => 'Please  enter address length upto 255 character only.',


                    //     'state.required' => 'Please select state.',
                    //     'city.required' =>'Please select city.',
                    //    'pincode.required' => 'Please enter pincode.',
                    //     'pincode.regex' => 'Please enter a 6-digit pincode.',
                    //     'user_profile.required' => 'The user profile is required.',
                    //     'user_profile.image' => 'The user profile must be a valid image file.',
                    //     'user_profile.mimes' => 'The user profile must be in JPEG, PNG, JPG, GIF, or SVG format.',
                    //     'user_profile.max' => 'The user profile size must not exceed '.Config::get("AllFileValidation.USER_PROFILE_MAX_SIZE").'KB .',
                    //     'user_profile.min' => 'The user profile size must not be less than '.Config::get("AllFileValidation.USER_PROFILE_MIN_SIZE").'KB .',
                    //     'user_profile.dimensions' => 'The user profile dimensions must be between 150x150 and 400x400 pixels.',    

                      ];


        $validation = Validator::make($request->all(),$rules,$messages);
        if($validation->fails() )
        {
            return redirect('add-users')
            ->withInput()
            ->withErrors($validation);
        }
        else
        {
            $register_user = $this->service->register($request);
          
            if($register_user)
            {
              
                $msg = $register_user['msg'];
                $status = $register_user['status'];
                if($status=='success') {
                    return redirect('list-users')->with(compact('msg','status'));
                }
                else {
                    return redirect('add-users')->withInput()->with(compact('msg','status'));
                }
            }
            
        }


    }

    public function show(Request $request)
    {
        try {
            $labour_detail = $this->service->getById($request->show_id);
            // dd($labour_detail);
            return view('admin.pages.labours.show-labour', compact('labour_detail'));
        } catch (\Exception $e) {
            return $e;
        }
    }
  
   
    public function delete(Request $request){
        try {
            $delete = $this->service->delete($request->delete_id);
            if ($delete) {
                $msg = $delete['msg'];
                $status = $delete['status'];
                if ($status == 'success') {
                    return redirect('list-users')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function updateOne(Request $request){
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-labours')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    public function editUsersProfile(Request $request){
        $user_data = $this->service->getProfile($request);
        // $user_detail= session()->get('user_id');
        // $id = $user_data->id;
        // return view('admin.layout.master',compact('user_data'));
        return view('admin.pages.users.edit-user-profile',compact('user_data'));
    }

    public function updateProfile(Request $request){
        $rules = [
            // 'email' => 'required',
            // 'password' => 'required',
            // 'number' => 'regex:/^\d{10}$/',
         ];       

        $messages = [   
                        // 'email.required' => 'Please enter email.',
                        // 'email.email' => 'Please enter valid email.',
                        // 'password.required' => 'Please enter password.',
                        // 'number.regex' => 'Please enter 10 digit number.',
                    ];


        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->updateProfile($request);
                // dd($register_user);
                if($register_user)
                {
                    if((isset($register_user['password_change']) && ($register_user['password_change'] =='yes')) || (isset($register_user['mobile_change']) && $register_user['mobile_change'] =='yes')) {
                        return view('admin.pages.users.otp-verify')->with(compact('register_user'));
                    }
                    elseif((isset($request->password) && $request->password !== '') && ($request->number == $request->old_number)) {
                        
                        return redirect('log-out');

                    }
                

                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('/dashboard')->with('msg','status');
                    }
                    else {
                        return redirect('/dashboard')->withInput()->with(compact('msg','status'))->with('success', 'Data updated successfully');
                    }
                }
                
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }
   

    public function updateEmailOtp(Request $request){
        $rules = [
            'otp_number' => 'required|numeric', // Add validation rules for otp_number field
        ];
    
        $messages = [
            'otp_number.required' => 'Please enter the OTP.',
            'otp_number.numeric' => 'The OTP must be a numeric value.',
        ];
    
        try {
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                // $verification_result = $this->service->verifyOtp($request->otp_number);
                $update_data = array();
                $return_data = array();
                $otp = User::where('id', $request->user_id)->first();
                if($otp->otp == $request->otp_number) {
                    
                    if($request->password_change =='yes') {
                        $update_data['password'] = $request->password_new;
                    }
                    if($request->mobile_change =='yes') {
                        $update_data['number'] = $request->new_mobile_number;
                    }
            
                    User::where('id', $request->user_id)->update($update_data);
                    $return_data['msg'] = 'Please login again to use services';
                    $return_data['msg_alert'] = 'green';
                                
                    $request->session()->flush();
                    $request->session()->regenerate();
                    return view('admin.login',compact('return_data'));
                    // return redirect('/login')->with('return_data', $return_data);

                } else {
                    $register_user = array();
                    $register_user['user_id'] = $request->user_id;
                    $register_user['password_new'] = $request->password_new;
                    $register_user['password_change'] = $request->password_change;
                    $register_user['new_mobile_number'] = $request->new_mobile_number;
                    $register_user['mobile_change'] = $request->mobile_change;
                    $register_user['msg'] = 'Please Enter Valid OTP';
                    $register_user['msg_alert'] = "red";


                    // return redirect()->back()
                    //     ->withInput()
                    //     ->with(['msg' => 'Invalid OTP.', 'status' => 'error']);
                    return view('admin.pages.users.otp-verify')->with(compact('register_user'));
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
  
}