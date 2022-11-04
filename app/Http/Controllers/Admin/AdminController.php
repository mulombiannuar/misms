<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Log;
use App\Models\Admin\Message;
use Illuminate\Http\Request;

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
    
}