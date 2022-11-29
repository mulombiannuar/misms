<?php

namespace App\Http\Controllers\Hostel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelRequest;
use App\Http\Requests\UpdateHostelRequest;
use App\Models\Hostel\Hostel;
use App\Models\Hostel\Room;
use App\Models\Hostel\StudentRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = [
			'page_name' => 'hostels',
            'title' => 'Manage Hostels',
            'hostels' => Hostel::getHostels(),
        ];
        return view('admin.hostel.index', $pageData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
			'page_name' => 'hostels',
            'title' => 'Create Hostel',
            'users' => User::getUsersByCategory(0),
        ];
        return view('admin.hostel.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHostelRequest $request)
    {
        $hostel = new Hostel;
        $hostel->hostel_name = $request->input('hostel_name'); 
        $hostel->hostel_capacity = $request->input('hostel_capacity'); 
        $hostel->hostel_master = $request->input('hostel_master'); 
        $hostel->hostel_motto = $request->input('hostel_motto'); 
        $hostel->created_by = Auth::user()->id; 
        $hostel->save();

        //Save audit trail
        $activity_type = 'New Hostel Creation';
        $description = 'Created new hostel  '.$hostel->hostel_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('hostel.hostels.index'))->with('success', 'Hostel data saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hostel = Hostel::findHostelById($id);
        $pageData = [
			'page_name' => 'hostels',
            'hostel' => Hostel::findHostelById($id),
            'rooms' => Room::getRoomsByHostelId($id),
            'title' => ucwords($hostel->hostel_name),
            'students' => Hostel::getStudentsByHostelId(0),
        ];
        return view('admin.hostel.show', $pageData);
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
			'page_name' => 'hostels',
            'title' => 'Edit Hostel',
            'hostel' => Hostel::findHostelById($id),
            'users' => User::getUsersByCategory(0),
        ];
        return view('admin.hostel.edit', $pageData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHostelRequest $request, $id)
    {
        $hostel = Hostel::find($id);
        $hostel->hostel_name = $request->input('hostel_name'); 
        $hostel->hostel_capacity = $request->input('hostel_capacity'); 
        $hostel->hostel_master = $request->input('hostel_master'); 
        $hostel->hostel_motto = $request->input('hostel_motto'); 
        $hostel->save();

        //Save audit trail
        $activity_type = 'Hostel Updation';
        $description = 'Updated hostel  '.$hostel->hostel_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('hostel.hostels.index'))->with('success', 'Hostel data saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Hostel::where('hostel_id', $id)->delete();
        Room::where('hostel_id', $id)->delete();
        StudentRoom::where('hostel_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Hostel Deletion';
        $description = 'Deleted existing hostel of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Hostel data and associated rooms deleted successfully');
    }
}