<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class UserController extends Controller
{
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $pageData = [
			'page_name' => 'dashboard',
            'title' => 'User Dashboard',
        ];
        return view($this->getUserDashboard(), $pageData);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function profile()
    {
        $pageData = [
			'page_name' => 'profile',
            'title' => 'User Profile',
        ];
        return view('admin.user.profile', $pageData);
    }

    public function password()
    {
        $pageData = [
            'user' => Auth::user(),
            'page_name' => 'password',
            'title' => 'Change Password',
        ];
        return view('user.change_password', $pageData);
    }

    public function passwordChange(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' =>  ['required', 'string', new Password, 'confirmed'],
        ]);

        if(!Hash::check($request->input('current_password'), $user->password)) 
        return back()->with('danger', 'The provided password does not match your current password. Please try again');
        
        if(Hash::check($request->input('password'), $user->password)) 
        return back()->with('danger', 'New password cannot be the same as the older password. Please try again with a different password');

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->save();

        //Save user log
        $activity_type = 'Password Change';
        $description = 'Updated account password successfully';
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Password changed successfully');
    }

    private function getUserDashboard()
    {
        $user = Auth::user();
        
        // Admin dashboard
        if ($user->hasRole('admin')) {
           return 'admin.user.admin_dashboard';
        }

        // Teacher dashboard
        if ($user->hasRole('teacher')) {
            return 'admin.user.teacher_dashboard';
         }
        return 'admin.user.user_dashboard';
    }

    ////////////..end....///////////
}