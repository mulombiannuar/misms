@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <div class="card card-warning card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#term-reports" data-toggle="tab"><i
                                class="fa fa-user"></i>
                            Term Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="#mean-reports" data-toggle="tab"><i
                                class="fa fa-users"></i>
                            Mean Reports</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="term-reports">
                        <!-- Term Reports -->
                        <x-card class="card-secondary" icon="fa-briefcase" title="Term Reports">
                            <form role="form" action="{{ route('marks.reports.type') }}">
                                @csrf
                                <div class="row">
                                    <x-form.select class="col-md-6 col-sm-12" value="" label="Report types"
                                        name="report_type">
                                        <option value="">- Select Report type -</option>
                                        <option value="1">Class Broadsheet</option>
                                        <option value="2">Grades Distribution</option>
                                        <option value="3">Subjects Analysis</option>
                                        <option value="4">Streams Ranking</option>
                                        <option value="9">Streams Improvement</option>
                                        <option value="5">Students Improvement</option>
                                        <option value="6">Subjects Improvement</option>
                                        {{-- <option value="7">Students Attendance</option> --}}
                                        <option value="8">Students Report Cards</option>
                                    </x-form.select>

                                    <x-form.select class="col-md-3 col-sm-12" value="" label="Student Class"
                                        name="section_numeric">
                                        @foreach ($forms as $form)
                                            <option value="{{ $form->form_numeric }}">
                                                {{ $form->form_name }}</option>
                                        @endforeach
                                    </x-form.select>

                                    <x-form.select class="col-md-3 col-sm-12" value="" label="Exams" name="exams">
                                        <option value="">- Select Exam -</option>
                                    </x-form.select>
                                    <!-- /.card-body -->
                                    <div class="modal-footer justify-content-between col-sm-12">
                                        <button type="submit" class="btn btn-secondary"> <i class="fa fa-calendar"></i>
                                            View Exam Report</button>
                                    </div>
                                </div>
                            </form>
                        </x-card>
                        <!-- /.Term Reports -->
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="mean-reports">
                        <!-- term mean analysis -->
                        <x-card class="card-secondary" icon="fa-edit" title="Mean Reports">
                            <form role="form" action="{{ route('marks.mean-reports.type') }}">
                                @csrf
                                <div class="row">
                                    <x-form.select class="col-md-6 col-sm-12" value="" label="Report types"
                                        name="reporttype">
                                        <option value="">- Select Report type -</option>
                                        <option value="1">Class Broadsheet</option>
                                        <option value="2">Grades Distribution</option>
                                        <option value="3">Subjects Analysis</option>
                                        <option value="4">Streams Ranking</option>
                                        <option value="5">Students Report Cards</option>
                                    </x-form.select>
                                    <x-form.select class="col-md-2 col-sm-12" value="" label="Class" name="class">
                                        @foreach ($forms as $form)
                                            <option value="{{ $form->form_numeric }}">
                                                {{ $form->form_name }}</option>
                                        @endforeach
                                    </x-form.select>

                                    <x-form.select class="col-md-2 col-sm-12" value="" label="year" name="year">
                                        <option value="">- Select Year -</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->year }}">{{ $year->year }}</option>
                                        @endforeach
                                    </x-form.select>

                                    <x-form.select class="col-md-2 col-sm-12" value="" label="Term" name="term">
                                        <option value="">- Select Term -</option>
                                        <option value="1">Term 1</option>
                                        <option value="2">Term 2</option>
                                        <option value="3">Term 3</option>
                                    </x-form.select>
                                    <!-- /.card-body -->
                                    <div class="modal-footer justify-content-between col-sm-12">
                                        <button type="submit" class="btn btn-secondary"> <i class="fa fa-calendar"></i>
                                            View Analysed Report</button>
                                    </div>
                                </div>
                            </form>
                        </x-card>
                        <!-- term mean analysis -->
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#section_numeric').change(function() {
                section_numeric = $('#section_numeric').val();
                if (section_numeric != '') {
                    $.ajax({
                        url: "{{ route('get.formexams') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            section_numeric: section_numeric
                        },
                        success: function(data) {
                            console.log(data);
                            $('#exams').html(data);
                        },
                        error: function(xhr, desc, err) {
                            console.log(xhr);
                            //console.log("Details0: " + desc + "\nError:" + err);
                        },
                    });
                } else {
                    $('#exams').html('<option value="">Select Form First</option>');
                }
            });
        });
    </script>
@endpush
