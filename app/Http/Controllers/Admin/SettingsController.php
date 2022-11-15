<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SchoolDetailsRequest;
use App\Http\Requests\PeriodRequest;
use App\Http\Requests\StoreDefaultGradeRequest;
use App\Models\Admin\DefaultGrade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use stdClass;

class SettingsController extends Controller
{
    /// School details
    public function schoolDetails()
    {
        $pageData = [
            'title' => 'School Details',
            'page_name' => 'settings',
            'school' => $this->getSchoolDetails()
          ];
          return view('admin.school_details', $pageData); 
    }

    public function saveSchoolDetails(SchoolDetailsRequest $request)
    {
       if($request->hasFile('logo')) {
           $fileNameWithExt = $request->file('logo')->getClientOriginalName();

           $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
           
           $extension = $request->file('logo')->getClientOriginalExtension();
           
           $logoImage = $fileName.'_'.time().'.'.$extension;

           $request->file('logo')->storeAs('public/assets/img/logo', $logoImage);
       }
       else{
           $logoImage = 'mulan-logo.png';
       }
       DB::table('school_details')->truncate();
       DB::table('school_details')->insert([
            'id' => 1,
            'logo' => $logoImage,
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'motto' => $request->input('motto'),
            'email' => $request->input('email'),
            'domain' => $request->input('domain'),
            'address' => $request->input('address'),
            'telephone' => $request->input('telephone'),
            'principal' => $request->input('principal'),
            'category' => $request->input('category'),
        ]);

        //Save user log
        $activity_type = 'School Details';
        $description = 'Updated school details successfully';
        User::saveUserLog($activity_type, $description);
        
        return back()->with('success', 'School data saved succesfully');
    }
   
    private function getSchoolDetails()
    {
        $details = DB::table('school_details')->first();
        if ($details) {
            return $details;
        }else{
            $school = new stdClass;
            $school->id = 1;
            $school->name = 'Mulan High School';
            $school->code = '650487975';
            $school->address = 'P.O Box 12588, Kitale';
            $school->telephone = '0703539209';
            $school->domain = 'mulan.co.ke';
            $school->principal = 'Anthony Wafula';
            $school->email = 'info@mulan.co.ke';
            $school->motto = 'Servicing Technology';
            $school->logo = 'mulan-logo.png';
            $school->category = 'Boarding School';
            return $school;
        }
    }

    /// Default gradings
    public function defaultGradings()
    {
        $grade = new DefaultGrade();
        $pageData = [
            'title' => 'Default Gradings',
            'page_name' => 'settings',
            'grades' => $grade->getDefaultGrades()
          ];
          return view('admin.default_gradings', $pageData); 
    }

    public function saveDefaultGrading(StoreDefaultGradeRequest $request)
    {
        $min_score = $request->input('min_score');
        $max_score = $request->input('max_score');
        $grade_name = $request->input('grade_name');
        $score_remarks = $request->input('score_remarks');

        //Truncate default_grades table
        DB::table('default_grades')->truncate();

        for ($count=0; $count <count($grade_name) ; $count++) 
        { 
            DB::table('default_grades')->insert([
                'min_score' => $min_score[$count],
                'max_score' => $max_score[$count],
                'grade_name' => $grade_name[$count],
                'score_remarks' => $score_remarks[$count],
                'created_by' => Auth::User()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //Save user log
        $activity_type = 'Default Grading Creation';
        $description = 'Successfully created new default grading';
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Default Grading data saved successfully');
    }
}