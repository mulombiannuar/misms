@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-warning" icon="fa-list-alt" :title="$title">
            <div class="text-right">
                <a href="{{ route('attendances.class-attendances.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Attendance"
                        buttonIcon="fa-plus-circle" />
                </a>

                <x-buttons.button class="margin mb-2 btn-info" buttonName="View Attendance Report" buttonIcon="fa-calendar"
                    data-toggle="modal" data-target="#modalReport" />
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>ATTENDANCES DATES</th>
                    <th>SESSION</th>
                    <th>CLASS</th>
                    <th>ACTION BY</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->attendance_id }}</td>
                            <td><strong>{{ date('D, d M Y', strtotime($attendance->date)) }}</strong></td>
                            <td>{{ 'Term ' . $attendance->term . ' - ' . $attendance->session }}</td>
                            <td>{{ $attendance->section_numeric . $attendance->section_name }}</td>
                            <td>{{ $attendance->teacher_name }}</td>
                            <td>
                                <a href="{{ route('attendances.class-attendances.show', $attendance->attendance_id) }}">
                                    <x-buttons.button class="btn btn-sm btn-info" buttonName="View Students"
                                        buttonIcon="fa-bars" />
                                </a>
                                <x-buttons.delete
                                    action="{{ route('attendances.class-attendances.destroy', $attendance->attendance_id) }}"
                                    btnSize="btn-sm" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>

            <x-form.modal id="modalReport" modalSize="modal-md" modalTitle="Students Attendance Report">
                <form role="form" action="{{ route('reports.pdfs.students') }}">
                    @csrf
                    <div class="row">
                        <x-form.select class="col-md-6 col-sm-12" value="" label="Student Class"
                            name="section_numeric">
                            @foreach ($forms as $form)
                                <option value="{{ $form->form_numeric }}">
                                    {{ $form->form_name }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select class="col-md-6 col-sm-12" value="" label="Sections" name="section">
                            <option value="">- Select Section -</option>
                        </x-form.select>

                        <x-form.input class="col-md-6 col-sm-6" label="Start Date" type="date" name="start_date"
                            placeholder="Select date start date" value="{{ date_format(date_create(now()), 'Y-m-d') }}" />

                        <x-form.input class="col-md-6 col-sm-6" label="End Date" type="date" name="end_date"
                            placeholder="Selext end date" value="{{ date_format(date_create(now()), 'Y-m-d') }}" />
                    </div>
                    <!-- /.card-body -->
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-info"> <i class="fa fa-calendar"></i>
                            View Attendance Report</button>
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
                } else {
                    $('#section').html('<option value="">Select Form First</option>');
                }
            });

        });
    </script>
@endpush
