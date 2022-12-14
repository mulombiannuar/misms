@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-secondary" icon="fa-briefcase" :title="$title">
            <form role="form" action="{{ route('marks.reports.type') }}">
                @csrf
                <div class="row">
                    <x-form.select class="col-md-4 col-sm-12" value="" label="Report types" name="report_type">
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

                    <x-form.select class="col-md-4 col-sm-12" value="" label="Student Class" name="section_numeric">
                        @foreach ($forms as $form)
                            <option value="{{ $form->form_numeric }}">
                                {{ $form->form_name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select class="col-md-4 col-sm-12" value="" label="Exams" name="exams">
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
