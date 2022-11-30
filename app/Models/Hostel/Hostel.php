<?php

namespace App\Models\Hostel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Hostel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'hostels';
    protected $primaryKey = 'hostel_id';

    
    public static function getHostels()
    {
       return DB::table('hostels')
                ->join('users', 'users.id', '=', 'hostels.hostel_master')
                ->select('hostels.*', 'users.name', 'users.id')
                ->where('hostels.deleted_at', null)
                ->orderBy('hostel_name', 'asc')
                ->get();
    }

    public static function getHostelsDetails()
    {
       $hostels = Hostel::getHostels();
       for ($s=0; $s <count($hostels) ; $s++) 
       { 
            $hostels[$s]->totalCapacity = StudentRoom::where('hostel_id', $hostels[$s]->hostel_id)->count();
            $hostels[$s]->totalBeds =  BedSpace::where('hostel_id', $hostels[$s]->hostel_id)->count();
            $hostels[$s]->totalRooms = Room::where('hostel_id', $hostels[$s]->hostel_id)->count();
       }
       return $hostels;
    }


    public static function findHostelById($id)
    {
        return DB::table('hostels')
                 ->join('users', 'users.id', '=', 'hostels.hostel_master')
                 ->select('hostels.*', 'users.name', 'users.id')
                 ->where('hostel_id', $id)
                 ->first();
    }

    public static function getStudentsByHostelId($id)
    {

    }
}