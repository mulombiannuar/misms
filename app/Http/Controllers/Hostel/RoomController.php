<?php

namespace App\Http\Controllers\Hostel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Hostel\BedSpace;
use App\Models\Hostel\Room;
use App\Models\Hostel\StudentRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
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
            'title' => 'Manage Rooms',
            'rooms' => Room::getRooms(),
        ];
        return view('admin.hostel.room.index', $pageData);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoomRequest $request)
    {
        //Remember to introduce room prefect
        $room = new Room();
        $room->room_label = $request->input('room_label'); 
        $room->room_capacity = $request->input('room_capacity'); 
        $room->hostel_id = $request->input('hostel_id'); 
        //$room->student_id = $request->input('student_id'); 
        $room->created_by = Auth::user()->id; 
        $room->save();

        //Save audit trail
        $activity_type = 'New Room Label Creation';
        $description = 'Created new room label  '.$room->room_label;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'New room label saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::find($id);
        $pageData = [
            'room' => $room,
			'page_name' => 'hostels',
            'title' => ucwords($room->room_label.' Manage Students'),
            'beds' => BedSpace::getBedSpacesByRoomId($id),
            'students' => Room::getStudentsRoomById($id),
        ];
        return view('admin.hostel.room.show', $pageData);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoomRequest $request, $id)
    {
        $room = Room::find($id);
        $room->room_label = $request->input('room_label'); 
        $room->room_capacity = $request->input('room_capacity'); 
        //$room->student_id = $request->input('student_id');
        $room->save();

        //Save audit trail
        $activity_type = 'Room Label Updation';
        $description = 'Updated room label  '.$room->room_label;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Room label updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Room::where('room_id', $id)->delete();
        BedSpace::where('room_id', $id)->delete();
        StudentRoom::where('room_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Room Deletion';
        $description = 'Deleted existing room of id '.$id;
        User::saveUserLog($activity_type, $description);
    
        return back()->with('success', 'Room data and associated occupants deleted successfully');
    }

    public function bedSpaces()
    {
        $pageData = [
			'page_name' => 'hostels',
            'title' => 'Manage Bed Spaces',
            'rooms' => Room::getRooms(),
        ];
        return view('admin.hostel.room.bed_spaces', $pageData);
    }

    public function getBedSpaces()
    {
        $beds = BedSpace::getBedSpaces();
        return DataTables::of($beds)->addIndexColumn()->make(true); 
    }

    public function saveBedSpace(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
            'space_label' => 'max:255|required|string|unique:bed_spaces'
        ]);
        
        $room_id = $request->input('room_id');
        $room = Room::find($room_id);
        $occupiedRooms = BedSpace::where('room_id', $room_id)->count();

        if($occupiedRooms >= $room->room_capacity)
        return back()->with('warning', 'Number of available bed spaces cannot exceed room capacity which is set to'. $room->room_capacity. '. You can instead adjust room capacity value');
        
        $bed = new BedSpace();
        $bed->room_id = $request->input('room_id'); 
        $bed->hostel_id = $room->hostel_id; 
        $bed->space_label = $request->input('space_label'); 
        $bed->created_by = Auth::user()->id; 
        $bed->save();

        //Save audit trail
        $activity_type = 'Bed Space Label Creation';
        $description = 'Created new bed space label  '.$bed->space_label;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'New bed space label saved successfully');
    }

    public function deleteBedSpace($id)
    {
        BedSpace::where('bed_id', $id)->delete();
        StudentRoom::where('bed_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Bed Space Deletion';
        $description = 'Deleted existing bed space of id '.$id;
        User::saveUserLog($activity_type, $description);
    
        return back()->with('success', 'Bed space data and associated occupants deleted successfully');
    }

}