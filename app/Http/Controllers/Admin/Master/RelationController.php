<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relation;
use App\Http\Services\Admin\Master\RelationServices;
use Validator;
use Illuminate\Validation\Rule;
class RelationController extends Controller
{

   public function __construct()
    {
        $this->service = new RelationServices();
    }
    public function index()
    {
        try {
            $relation_data = $this->service->getAll();
            return view('admin.pages.master.relation.list-relation', compact('relation_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.relation.add-relation');
    }

    public function store(Request $request) {
        $rules = [
            'relation' => 'required|unique:relation|regex:/^[a-zA-Z\s]+$/u|max:255',
            // 'marathi_title' => 'required|unique:relation|max:255',
         ];
        $messages = [   
            'relation'       =>  'Please enter title.',
            'relation.regex' => 'Please  enter text only.',
            'relation.unique' => 'Title already exist.',
            // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
            'relation.max'   => 'Please  enter text length upto 255 character only.',
            // 'marathi_title.required'       =>'कृपया शीर्षक प्रविष्ट करा.',
            // 'marathi_title.unique'  =>  'तुमचा घटना शीर्षक आधीपासून अस्तित्वात आहे .',
            // 'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-relation')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_relation_data = $this->service->addAll($request);
                if($add_relation_data)
                {

                    $msg = $add_relation_data['msg'];
                    $status = $add_relation_data['status'];
                    if($status=='success') {
                        return redirect('list-relation')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-relation')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-relation')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $relation_data = $this->service->getById($edit_data_id);
        return view('admin.pages.master.relation.edit-relation', compact('relation_data'));
   
    }
    // public function update(Request $request)
    // {
    //     $rules = [
    //         // 'relation' => 'required|unique:relation|regex:/^[a-zA-Z\s]+$/u|max:255',
    //         // 'marathi_title' => 'required|unique:relation|max:255',
    //         'relation'      => ['required','max:255',Rule::unique('relation', 'name')->ignore($this->id, 'id')],
    //         'marathi_title'      => ['required','max:255',Rule::unique('relation', 'name')->ignore($this->id, 'id')]
           
    //      ];
    //     $messages = [   
    //         'relation'       =>  'Please  enter english title.',
    //         'relation.regex' => 'Please  enter text only.',
    //         'relation.unique' => 'Title already exist.',
    //         'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    //         'relation.max'   => 'Please  enter text length upto 255 character only.',
    //         'marathi_title'       =>'कृपया शीर्षक प्रविष्ट करा.',
    //         'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
    //     ];


    //     try {
    //         $validation = Validator::make($request->all(),$rules, $messages);
    //         if ($validation->fails()) {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->withErrors($validation);
    //         } else {
    //             $update_relation_data = $this->service->updateAll($request);
    //             if ($update_relation_data) {
    //                 $msg = $update_relation_data['msg'];
    //                 $status = $update_relation_data['status'];
    //                 if ($status == 'success') {
    //                     return redirect('list-relation')->with(compact('msg', 'status'));
    //                 } else {
    //                     return redirect()->back()
    //                         ->withInput()
    //                         ->with(compact('msg', 'status'));
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with(['msg' => $e->getMessage(), 'status' => 'error']);
    //     }
    // }

    public function update(Request $request)
{
    $id = $request->input('id'); // Assuming the 'id' value is present in the request
    $rules = [
        'relation' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('relation', 'relation')->ignore($id, 'id')],
        // 'marathi_title' => ['required', 'max:255', Rule::unique('relation', 'marathi_title')->ignore($id, 'id')],
    ];

    $messages = [
        'relation.required' => 'Please enter an title.',
        'relation.regex' => 'Please  enter text only.',
        'relation.max' => 'Please enter an  title with a maximum of 255 characters.',
        'relation.unique' => 'The title already exists.',
        // 'marathi_title.required' => 'कृपया  शीर्षक प्रविष्ट करा.',
        // 'marathi_title.max' => 'कृपया २५५ अक्षरांपर्यंत  शीर्षक प्रविष्ट करा.',
        // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    ];

    try {
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validation);
        } else {
            $update_relation_data = $this->service->updateAll($request);

            if ($update_relation_data) {
                $msg = $update_relation_data['msg'];
                $status = $update_relation_data['status'];

                if ($status == 'success') {
                    return redirect('list-relation')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        }
    } catch (Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with(['msg' => $e->getMessage(), 'status' => 'error']);
    }
}

    public function show(Request $request)
    {
        try {
            $relation_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.relation.show-relation', compact('relation_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-relation')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $relation_data = $this->service->deleteById($request->delete_id);
            if ($relation_data) {
                $msg = $relation_data['msg'];
                $status = $relation_data['status'];
                if ($status == 'success') {
                    return redirect('list-relation')->with(compact('msg', 'status'));
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

}