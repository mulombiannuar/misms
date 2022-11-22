<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use App\Models\Academic\Form;
use App\Models\User;
use Illuminate\Http\Request;

class FormController extends Controller
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
            'title' => 'Manage Classes',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get()
        ];
        return view('admin.academic.forms.index', $pageData);//
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
            'title' => 'Create Classes',
        ];
        return view('admin.academic.forms.create', $pageData);//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormRequest $request)
    {
        $form = new Form;
        $form->min_subs = $request->input('min_subs'); 
        $form->max_subs = $request->input('max_subs'); 
        $form->form_name = $request->input('form_name'); 
        $form->form_numeric = $request->input('form_numeric'); 
        $form->save();

        //Save audit trail
        $activity_type = 'New Class Creation';
        $description = 'Created new class  '.$form->form_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.forms.index'))->with('success', 'Class saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageData = [
			'page_name' => 'classes',
            'title' => 'Show Class',
            'form' => Form::find($id)
        ];
        return view('academic.show', $pageData);//
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
            'title' => 'Update Class',
            'form' => Form::find($id)
        ];
        return view('admin.academic.forms.edit', $pageData);//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFormRequest $request, $id)
    {
        $form = Form::find($id);
        $form->min_subs = $request->input('min_subs'); 
        $form->max_subs = $request->input('max_subs'); 
        $form->form_name = $request->input('form_name'); 
        $form->form_numeric = $request->input('form_numeric'); 
        $form->save();

        //Save audit trail
        $activity_type = 'Class Updation';
        $description = 'Updated existing class  '.$form->form_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.forms.index'))->with('success', 'Class saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Form::where('form_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Class Deletion';
        $description = 'Deleted existing class of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Class data deleted successfully');
    }
}