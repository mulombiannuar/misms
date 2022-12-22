@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-warning" icon="fa-list-alt" :title="$title">
            <div class="text-right">
                <x-buttons.button class="margin mb-2 btn-secondary" buttonName="Create New Scores Record"
                    buttonIcon="fa-plus-circle" data-toggle="modal" data-target="#modalScoresSubmission" />
            </div>
            <x-table.table id="datatable">
                <x-table.thead>
                    <th>S.N</th>
                    <th>DATES</th>
                    <th>EXAM NAMES</th>
                    <th>SUBJECTS</th>
                    <th>CLASS</th>
                    <th>SECTION</th>
                    <th>ACTION BY</th>
                    <th>SHOW</th>
                    <th>DELETE</th>
                </x-table.thead>
                <tbody>

                </tbody>
            </x-table.table>

            <x-form.modal id="modalScoresSubmission" modalSize="modal-lg" modalTitle="Scores Exam Details">
                <form role="form" action="{{ route('marks.submitted-scores.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <x-form.select class="col-md-4 col-sm-12" value="" label="Subjects" name="subject">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->subject_id }}">
                                    {{ $subject->subject_name }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select class="col-md-4 col-sm-12" value="" label="Student Class"
                            name="section_numeric">
                            @foreach ($forms as $form)
                                <option value="{{ $form->form_numeric }}">
                                    {{ $form->form_name }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select class="col-md-4 col-sm-12" value="" label="Sections" name="section">
                            <option value="">- Select Section -</option>
                        </x-form.select>

                        <x-form.select class="col-md-12 col-sm-12" value="" label="Exams" name="exams">
                            <option value="">- Select Exam -</option>
                        </x-form.select>

                    </div>
                    <!-- /.card-body -->
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-info"> <i class="fa fa-plus-circle"></i>
                            Save Scores Records</button>
                    </div>
                </form>
            </x-form.modal>

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
                        url: "{{ route('get.formsections') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            section_numeric: section_numeric
                        },
                        success: function(data) {
                            console.log(data);
                            $('#section').html(data);
                        },
                        error: function(xhr, desc, err) {
                            console.log(xhr);
                            //console.log("Details0: " + desc + "\nError:" + err);
                        },
                    });

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
                    $('#section').html('<option value="">Select Form First</option>');
                }
            });

            $("#datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('marks.submitted-scores.getscores') }}",
                columns: [{
                        data: 'subm_id',
                        name: 'subm_id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'exam_name',
                        name: 'exam_name'
                    },
                    {
                        data: 'subject_name',
                        name: 'subject_name'
                    },
                    {
                        data: 'section_numeric',
                        name: 'section_numeric'
                    },
                    {
                        data: 'section_name',
                        name: 'section_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'show',
                        name: 'show',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'delete',
                        name: 'delete',
                        orderable: false,
                        searchable: false
                    },

                ]
            });

        });
    </script>
@endpush
