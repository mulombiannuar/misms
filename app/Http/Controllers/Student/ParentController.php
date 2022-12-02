<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Models\Student\Parents;
use App\Models\Student\Student;
use App\Models\Student\StudentParent;
use App\Models\User;
use App\Utilities\Buttons;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return $this->getParents();
        $pageData = [
			'page_name' => 'parents',
            'title' => 'Manage Parents',
        ];
        return view('admin.parent.index', $pageData);
    }

    public function getParents()
    {
        $parents = Parents::orderBy('name', 'asc')->get();
        return DataTables::of($parents)
                        ->addIndexColumn()
                        ->addColumn('action', function ($parent) {
                            return Buttons::dataTableButtons(
                                route('students.parents.show', $parent->parent_id),
                                route('students.parents.edit', $parent->parent_id),
                                route('students.parents.destroy', $parent->parent_id),
                            );
                         })
                        ->rawColumns(['action'])
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
			'page_name' => 'parents',
            'title' => 'Add New Parent',
        ];
        return view('admin.parent.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParentRequest $request)
    {
        $parent = new Parents();
        $parent->status = 1;
        $parent->type = $request->input('type');
        $parent->name = $request->input('name');
        $parent->receive_sms = $request->input('receive_sms');
        $parent->address = $request->input('address');
        $parent->profession = $request->input('profession');
        $parent->mobile_no = $request->input('mobile_no');
        $parent->gender = $request->input('gender');
        $parent->save();

        if ($request->has_student) {
            $student = Student::find($request->student_id);
            $student->parents()->attach($parent->parent_id);
        }

        //Save user log
        $activity_type = 'Parent Creation';
        $description = 'Successfully created new parent ';
        User::saveUserLog($activity_type, $description);
 
        return back()->with('success', 'Parent successfully saved');
    }

    public function addExistingParent(Request $request)
    {
        $parent_id = $request->input('parent');
        $student_id = $request->input('student_id');
        if (StudentParent::where(['parent_id' => $parent_id, 'student_id' => $student_id])->first()) 
        {
            return back()->with('warning', 'Parent already attached to the student'); 
        }
        
        $student = Student::find($student_id);
        $student->parents()->attach($parent_id);
        return back()->with('success', 'Parent successfully attached to the student');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return Parents::getParentStudents($id);
        $pageData = [
			'page_name' => 'parents',
            'title' => 'Parent Information',
            'parent' => Parents::find($id),
        ];
        return view('admin.parent.show', $pageData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageData = [
			'page_name' => 'parents',
            'title' => 'Edit Parent Information',
            'parent' => Parents::find($id),
        ];
        return view('admin.parent.edit', $pageData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParentRequest $request, $id)
    {
        $parent = Parents::find($id);
        $parent->type = $request->input('type');
        $parent->name = $request->input('name');
        $parent->receive_sms = $request->input('receive_sms');
        $parent->address = $request->input('address');
        $parent->profession = $request->input('profession');
        $parent->mobile_no = $request->input('mobile_no');
        $parent->save();

        //Save user log
        $activity_type = 'Parent Updation';
        $description = 'Successfully updated parent '.$parent->name;
        User::saveUserLog($activity_type, $description);
 
        return redirect(route('students.parents.index'))->with('success', 'Parent successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StudentParent::where('parent_id', $id)->delete();
        Parents::where('parent_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Parent Deletion';
        $description = 'Deleted parent of id  '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Parent data deleted successfully with its associated students');
    }
}