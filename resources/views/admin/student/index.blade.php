@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <div class="text-right">
            <a href="{{ route('students.students.create') }}">
                <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Student" buttonIcon="fa-plus" />
            </a>
        </div>
        <x-card class="card-warning" icon="fa-users" title="Manage Students">
            <x-table.table id="datatable">
                <x-table.thead>
                    <th>S.N</th>
                    <th>ADM. NO</th>
                    <th>NAMES</th>
                    <th>EMAILS</th>
                    <th>GENDER</th>
                    <th>CLASS</th>
                    <th>SECTION</th>
                    <th>DOB DATE</th>
                    <th>STS</th>
                    <th>ACTIONS</th>
                    <th>RESET</th>
                </x-table.thead>
            </x-table.table>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('students.get.students') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'admission_no',
                        name: 'admission_no'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
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
                        data: 'birth_date',
                        name: 'birth_date'
                    },
                    {
                        data: 'action_view',
                        name: 'action_view',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'reset',
                        name: 'reset',
                        orderable: false,
                        searchable: false
                    },

                ]
            });
        });
    </script>
@endpush
