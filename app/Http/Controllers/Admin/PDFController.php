<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PDFController extends Controller
{
    public function studentsClassAttendanceReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'section' => 'required|integer'
        ]);

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $section_id = $request->section;

        $data = ClassAttendance::getReportDateRanges($section_id, $start_date, $end_date);
        //return $data['message'];
        if($data['error']) return back()->with('warning', $data['message']);
        
        $pageData = [
			'page_name' => 'marks',
			'title' => 'Students Attendance Report',
            'data' => $data
        ];
        
        $html = view('reports.pdfs.students_attendance_report', $pageData);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4','landscape');
        return $pdf->stream('students_attendance_report.pdf', array('Attachment' => 0));
    }
}