<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Academic\Form;
use App\Models\Academic\Section;
use App\Models\User;
use Illuminate\Http\Request;

class SectionController extends Controller
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
            'title' => 'Manage Sections',
            'sections'=> Section::orderBy('section_numeric', 'asc')->get(),
        ];
        return view('admin.academic.sections.index', $pageData);//
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
            'title' => 'Add Sections',
            'users' => User::getUsersByCategory(0),
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get()
        ];
        return view('admin.academic.sections.create', $pageData);//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSectionRequest $request)
    {
        //return $request;
        $section = new Section;
        $section->section_name = $request->input('section_name'); 
        $section->section_numeric = $request->input('section_numeric'); 
        $section->section_teacher = $request->input('section_teacher'); 
        $section->save();

        //Save audit trail
        $activity_type = 'New Section Creation';
        $description = 'Created new section  '.$section->section_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.sections.index'))->with('success', 'Section data saved successfully');
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
            'title' => 'Edit Sections',
            'section' =>  Section::find($id),
            'users' => User::getUsersByCategory(0),
        ];
        return view('admin.academic.sections.edit', $pageData);//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->section_name = $request->input('section_name'); 
        //$section->section_numeric = $request->input('section_numeric'); 
        $section->section_teacher = $request->input('section_teacher'); 
        $section->save();

        //Save audit trail
        $activity_type = 'Section Updation';
        $description = 'Updated section  '.$section->section_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.sections.index'))->with('success', 'Section data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Section::where('section_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Section Deletion';
        $description = 'Deleted existing section of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Section data deleted successfully');
    }
}