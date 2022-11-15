<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Log;
use App\Models\Admin\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function messages()
    {
        $pageData = [
            'page_name' => 'messages',
            'title' => 'Messages',
            'messages'=> Message::orderBy('message_id', 'desc')->get(),
        ];
        return view('admin.messages', $pageData);
    }
    
    public function logs()
    {
        $pageData = [
          'title' => 'System Logs',
          'page_name' => 'logs',
          'logs' => Log::getLogs()
        ];
        return view('admin.logs', $pageData);
    }

    public function fetchSubCounties(Request $request)
    {
        $county_id =  $request->input('county');
        $subs = DB::table('sub_counties')->where('county_id', $county_id)->get();
        $output = '<option value="">- Select Sub County -</option>'; 
        foreach($subs as $row)
        {
          $output .= '<option value="'.$row->sub_id.'">'.$row->sub_name.'</option>';
        }
        return $output; 
    }
   
    
}