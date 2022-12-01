<?php

namespace App\Models;

use App\Models\Student\Student;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait, SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'student_user_id', 'id');
    }

    public static function getUserIpAddress()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function saveUserLog($activity_type, $description)
    {
        //Save user log
        return  DB::table('logs')->insert([
            'user_id' => Auth::user()->id,
            'description' => $description,
            'activity_type' => $activity_type,
            'ip_address' => User::getUserIpAddress(),
            'created_at' => Carbon::now(),
            'date' => Carbon::now()
        ]);
    }

    public static function generatePassword()
    {
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
                  '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';
        $length = 10;
        $str = '';
        $max = strlen($chars) - 1;
      
        for ($i=0; $i < $length; $i++)
          $str .= $chars[random_int(0, $max)];
      
        return $str;
    }

    public static function getUsers()
    {
       return  DB::table('users')
                 ->where(['deleted_at' => null, 'accessibility'=> 1])
                 ->orderBy('name', 'asc')
                 ->get();
    }

    public static function getUsersByCategory($is_student)
    {
       return  User::where(['deleted_at' => null, 'accessibility'=> 1, 'is_student' => $is_student])
                    ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id' )
                    ->orderBy('name', 'asc')
                    ->get();
    }

    public static function getUserByMobileNumber($mobile_no)
    {
         return DB::table('users')
                  ->join('profiles', 'profiles.user_id', '=', 'users.id')
                  ->select('users.email','users.name','profiles.*')
                  ->where(['mobile_no' => $mobile_no, 'deleted_at' => null, 'accessibility' => 1, 'status' => 1])
                  ->first();
    }

    public static function getUserById($id)
    {
        return  DB::table('users')
                 ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id' )
                 ->leftJoin('sub_counties', 'sub_counties.sub_id', '=', 'sub_county')
                 ->leftJoin('counties', 'counties.county_id', '=', 'profiles.county')
                 ->where(['id' => $id, 'deleted_at' => null, 'accessibility' => 1,'status' => 1])
                 ->first();
    }

}