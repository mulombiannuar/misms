<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = [
            'title' => 'School Periods',
            'page_name' => 'settings',
            'periods' => DB::table('periods')->orderBy('period_name','asc')->get()
          ];
          return view('admin.period.index', $pageData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
            'title' => 'Add New Period',
            'page_name' => 'settings',
          ];
          return view('admin.period.create', $pageData); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeriodRequest $request)
    {
        DB::table('periods')->insert([
            'period_name' => $request->input('period'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'created_at' => Carbon::now()
        ]);

        //Save user log
        $activity_type = 'Period Creation';
        $description = 'Successfully saved new period '.$request->period;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.periods.index'))->with('success', 'Period added successfully');
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
            'title' => 'Edit Period',
            'page_name' => 'settings',
            'period' => DB::table('periods')->where('period_id', $id)->first()
          ];
          return view('admin.period.edit', $pageData); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePeriodRequest $request, $id)
    {
        DB::table('periods')->where('period_id', $id)->update([
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'updated_at' => Carbon::now()
        ]);

        //Save user log
        $activity_type = 'Period Updation';
        $description = 'Successfully updated period '.$request->period;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.periods.index'))->with('success', 'Period updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('periods')->where('period_id', $id)->delete();

        //Save user log
        $activity_type = 'Period Deletion';
        $description = 'Deleted school details successfully of id '.$id;
        User::saveUserLog($activity_type, $description);
        
        return back()->with('success', 'Period deleted successfully');
    }
}