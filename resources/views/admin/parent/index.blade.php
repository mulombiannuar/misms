@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <div class="text-right">
            <a href="{{ route('students.parents.create') }}">
                <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Parent" buttonIcon="fa-plus" />
            </a>
        </div>
        <x-card class="card-secondary" icon="fa-users" title="Manage Parents">
            <x-table.table id="datatable">
                <x-table.thead>
                    <th>S.N</th>
                    <th>TYPE</th>
                    <th>NAMES</th>
                    <th>PHONE</th>
                    <th>PROFESSION</th>
                    <th>ADDRESS</th>
                    <th>ACTIONS</th>
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
                ajax: "{{ route('students.parents.get-parents') }}",
                columns: [{
                        data: 'parent_id',
                        name: 'parent_id'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no'
                    },
                    {
                        data: 'profession',
                        name: 'profession'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                ]
            });
        });
    </script>
@endpush
