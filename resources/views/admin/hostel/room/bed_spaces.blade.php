@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-secondary" icon="fa-users" title="{{ $title }}">
            <x-table.table id="datatable">
                <x-table.thead>
                    <th>S.N</th>
                    <th>ADM NO.</th>
                    <th>STUDENT NAME</th>
                    <th>HOSTEL</th>
                    <th>ROOM</th>
                    <th>BED</th>
                </x-table.thead>
            </x-table.table>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
@push('scripts')
    <script>
        //https://datatables.yajrabox.com/eloquent/add-edit-remove-column#edit-13
        $(document).ready(function() {
            $("#datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('hostel.rooms.get-spaces') }}",
                columns: [{
                        data: 'student_no',
                        name: 'student_no'
                    },
                    {
                        data: 'student_admn',
                        name: 'student_admn'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'hostel_name',
                        name: 'hostel_name'
                    },
                    {
                        data: 'room_label',
                        name: 'room_label'
                    },
                    {
                        data: 'space_label',
                        name: 'space_label'
                    },
                ]
            });
        });
    </script>
@endpush
