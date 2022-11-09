<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Session;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = [
            'title' => 'Academic Sessions',
            'page_name' => 'settings',
            'sessions' => Session::getSessions()
          ];
        return view('admin.session.index', $pageData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
            'title' => 'Create Sessions',
            'page_name' => 'settings',
          ];
        return view('admin.session.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSessionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSessionRequest $request)
    {
        $data = [
            'term' => $request->term,
            'session' => $request->session,
            'opening_date' => $request->opening_date,
            'closing_date' => $request->closing_date,
        ];
        if(Session::where($data)->first())
        return redirect(route('admin.sessions.index'))->with('danger', 'Session with above details already exists');
       
        Session::insert($data);

        //Save user log
        $activity_type = 'Session Creation';
        $description = 'Successfully saved new session of opening date '.$request->opening_date.' an closing date of '.$request->closing_date;
        User::saveUserLog($activity_type, $description);
 
        return redirect(route('admin.sessions.index'))->with('success', 'Session added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        $pageData = [
            'title' => 'Show Sessions',
            'page_name' => 'settings',
            'session' => $session
          ];
        return view('admin.session.show', $pageData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function edit(Session $session)
    {
        $pageData = [
            'title' => 'Edit Sessions',
            'page_name' => 'settings',
            'session' => $session
          ];
        return view('admin.session.edit', $pageData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSessionRequest  $request
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSessionRequest $request, Session $session)
    {
        $session->update([
            'opening_date' => $request->opening_date,
            'closing_date' => $request->closing_date,
        ]);

         //Save user log
         $activity_type = 'Session Updation';
         $description = 'Successfully updated session of opening date '.$request->opening_date.' an closing date of '.$request->closing_date;
         User::saveUserLog($activity_type, $description);
 
         return redirect(route('admin.sessions.index'))->with('success', 'Session updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        $session->delete();

        //Save user log
        $activity_type = 'Session Deletion';
        $description = 'Successfully deleted session of opening date '.$session->opening_date.' an closing date of '.$session->closing_date;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.sessions.index'))->with('success', 'Session deleted successfully');  
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSessionRequest  $request
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */

     public function activateSession (Session $session)
     {
        DB::table('sessions')->where('status', 1)->update(['status' => 0]); 
        DB::table('sessions')->where('session_id', $session->session_id)->update(['status' => 1]);

        //Save user log
        $activity_type = 'Session Activation';
        $description = 'Successfully activated session of opening date '.$session->opening_date.' an closing date of '.$session->closing_date;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.sessions.index'))->with('success', 'Session acivated successfully'); 
     }


     
     /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSessionRequest  $request
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
     public function deactivateSession (Session $session )
     {
        Session::where('status', 1)->update(['status' => 0]); 
        $session->update(['status'=> 0]);

        //Save user log
        $activity_type = 'Session Deactivation';
        $description = 'Successfully deactivated session of opening date '.$session->opening_date.' an closing date of '.$session->closing_date;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.sessions.index'))->with('success', 'Session deactivated successfully'); 
     }
}