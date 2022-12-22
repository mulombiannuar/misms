@extends('layouts.app.report')

@section('content')
    <style>
        td,
        th {
            text-align: left;
            padding-bottom: 8px;
        }

        #rotate {
            -moz-transform: rotate(-90.0deg);
            -o-transform: rotate(-90.0deg);
            -webkit-transform: rotate(-90.0deg);
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
        }
    </style>
    <main>
        <h5 style="font: 13px; margin-bottom: 5px;">CLASS ATTENDANCE REPORT</h5>
        <div>
            <table id="table" class="table table-sm table-hover table-head-fixed text-nowrap" width="100%">
                <thead>
                    <tr>
                        <th width="2%">S.N</th>
                        <th>NAMES/DATES</th>
                        @foreach ($data['dates'] as $date)
                            <th><span>{{ $date }}</span></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['students'] as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->admission_no . ' - ' . $student->name }}</td>
                            @foreach ($student->attendances as $date)
                                <td>{{ $date->attendance_status ? 'X' : '--' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
