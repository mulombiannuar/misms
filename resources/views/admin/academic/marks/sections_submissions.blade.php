@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-warning card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    @foreach ($sections as $section)
                        <li class="nav-item"><a class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}"
                                href="#section-{{ $section->section_id }}" data-toggle="tab"><i class="fa fa-list"></i>
                                Form {{ strtoupper($section->section_numeric . $section->section_name) }}</a></li>
                    @endforeach
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($sections as $section)
                        <div class="tab-pane {{ $loop->iteration == 1 ? 'active' : '' }}"
                            id="section-{{ $section->section_id }}">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fa fa-list"></i>
                                        FORM {{ strtoupper($section->section_numeric . $section->section_name) }}</h3>
                                </div>
                                <div class="card-body">
                                    <x-table.table id="table{{ $loop->iteration }}">
                                        <x-table.thead>
                                            <th>S.N</th>
                                            <th>SUBJECTS</th>
                                            <th>DATES SUBMITTED</th>
                                            <th>ACTION BY</th>
                                            <th>SUBMITTED</th>
                                            <th>SHOW</th>
                                        </x-table.thead>
                                        <tbody>
                                            @foreach ($section->subjects as $subject)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><strong>{{ $subject->subject_name }}</strong></td>
                                                    <td>{{ $subject->submitted_date }}</td>
                                                    <td>{{ $subject->teacher }}</td>
                                                    <td>{{ $subject->is_submitted ? 'Yes' : 'Not' }}</td>
                                                    <td>
                                                        @if ($subject->is_submitted)
                                                            <a
                                                                href="{{ route('marks.submitted-scores.show', $subject->submission_id) }}">
                                                                <x-buttons.button class="btn-secondary btn-xs"
                                                                    buttonName="Show Scores" buttonIcon="fa-bars" />
                                                            </a>
                                                        @else
                                                            <x-buttons.button class="btn-danger btn-xs"
                                                                buttonName="No Scores!" buttonIcon="fa-bars" />
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </x-table.table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                    @endforeach
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </x-section>
    <!-- /.section component -->
@endsection
