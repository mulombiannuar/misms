<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Laravel\Fortify\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

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
    public function index(Request $request)
    {
        $pageData = [
			'page_name' => 'users',
            'title' => 'Manage Users',
            'users' => []
        ];
        return view('admin.user.index_datatable', $pageData);
    }


    public function getUsers(Request $request)
    {
        $users = User::select(['id', 'name', 'email', 'password', 'created_at', 'updated_at']);

        return Datatables::of($users)
                        ->addColumn('action', function ($user) {
                            return '<a href="#edit-'.$user->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                                    })
                        ->removeColumn('password')
                        ->make(true);

        return Datatables::of(User::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
			'page_name' => 'users',
            'title' => 'Create Users',
            'counties' => DB::table('counties')->orderBy('county_name', 'asc')->get(),
        ];
        return view('admin.user.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
       $newUser = new CreateNewUser();
       $user = $newUser->create($request->only(['name', 'email', 'password', 'password_confirmation', 'is_student']));
       $user->attachRole('teacher');

       //Send user password reset link
       FacadesPassword::sendResetLink(['email' => $user->email]);

       //Create user profile
       $profile = new Profile();
       $profile->user_id = $user->id;
       $profile->gender = $request->input('gender');
       $profile->county = $request->input('county');
       $profile->address = $request->input('address');
       $profile->national_id = $request->input('national_id');
       $profile->religion = $request->input('religion');
       $profile->mobile_no = $request->input('mobile_no');
       $profile->sub_county = $request->input('sub_county');
       $profile->birth_date = $request->input('birth_date');
       $profile->save();

       //Save audit trail
       $activity_type = 'User Creation';
       $description = 'Successfully created new user '.$user->name;
       User::saveUserLog($activity_type, $description);
       
       return redirect(route('admin.users.index'))->with('success' , 'User created successfully and password reset link sent');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageData = [
			'page_name' => 'users',
            'title' => 'Profile Information',
            'user' => User::getUserById($id)
        ];
        return view('admin.user.show', $pageData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //return User::getUserById($id);
        $pageData = [
            'page_name' => 'users',
            'user' => User::getUserById($id),
            'title' => 'Edit User Profile Information',
            'counties' => DB::table('counties')->orderBy('county_name', 'asc')->get(),
        ];
        return view('admin.user.edit', $pageData);
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
        $user->update($request->except(['_token']));
       
        //Update user profile
        $profile = Profile::find($user->profile->profile_id);
        $profile->user_id = $user->id;
        $profile->gender = $request->input('gender');
        $profile->county = $request->input('county');
        $profile->address = $request->input('address');
        $profile->national_id = $request->input('national_id');
        $profile->religion = $request->input('religion');
        $profile->mobile_no = $request->input('mobile_no');
        $profile->sub_county = $request->input('sub_county');
        $profile->birth_date = $request->input('birth_date');
        $profile->save();

       //Save audit trail
       $activity_type = 'User Updation';
       $description = 'Successfully updated user '.$user->name;
       User::saveUserLog($activity_type, $description);

       return redirect(route('admin.users.index'))->with('success' , 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        //Save audit trail
        $activity_type = 'User Deletion';
        $description = 'Deleted the user '.$user->name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.users.index'))->with('success' , 'User deleted successfully');
    }

    
    public function activateUser(User $user)
    {
        $user->status = 1;
        $user->save();

        //Save audit trail
        $activity_type = 'User Activation';
        $description = 'Activated the user '.$user->name;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'User activated successfully');  
    }

     public function deactivateUser(User $user)
    {
        $user->status = 0;
        $user->save();
        
        //Save audit trail
        $activity_type = 'User Deactivation';
        $description = 'Deactivated the user '.$user->name;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'User deactivated successfully');  
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