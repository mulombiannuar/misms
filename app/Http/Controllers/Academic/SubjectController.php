<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Academic\Subject;
use App\Models\User;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = [
			'page_name' => 'academics',
            'title' => 'Manage Subjects',
            'subjects' => Subject::orderBy('subject_name', 'asc')->get(),
        ];
        return view('admin.academic.subjects.index', $pageData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
			'page_name' => 'academics',
            'title' => 'Create Subjects',
        ];
        return view('admin.academic.subjects.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {
        $subject = new Subject;
        $subject->subject_name = $request->input('subject_name'); 
        $subject->subject_code = $request->input('subject_code'); 
        $subject->subject_short = $request->input('subject_short'); 
        $subject->group = $request->input('group'); 
        $subject->optionality = $request->input('optionality'); 
        $subject->save();

        //Save audit trail
        $activity_type = 'New Subject Creation';
        $description = 'Created new subject  '.$subject->subject_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.subjects.index'))->with('success', 'Subject data saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
			'page_name' => 'academics',
            'title' => 'Update Subjects',
            'subject' => Subject::find($id)
        ];
        return view('admin.academic.subjects.edit', $pageData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, $id)
    {
        $subject = Subject::find($id);
        $subject->group = $request->input('group'); 
        $subject->subject_name = $request->input('subject_name'); 
        $subject->subject_code = $request->input('subject_code'); 
        $subject->subject_short = $request->input('subject_short'); 
        $subject->optionality = $request->input('optionality'); 
        $subject->save();

        //Save audit trail
        $activity_type = 'Subject Updation';
        $description = 'Uodated subject  '.$subject->subject_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.subjects.index'))->with('success', 'Subject data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subject::where('subject_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Subject Deletion';
        $description = 'Deleted existing subject of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Subject data deleted successfully');
    }
}