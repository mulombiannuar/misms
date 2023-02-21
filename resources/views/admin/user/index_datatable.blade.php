@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <div class="text-right">
            <a href="{{ route('admin.users.create') }}">
                <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New User" buttonIcon="fa-plus" />
            </a>
        </div>
        <x-card class="card-secondary" icon="fa-users" title="Manage Users ({{ $users }})">
            <x-table.table id="datatable">
                <x-table.thead>
                    <th>S.N</th>
                    <th>NAMES</th>
                    <th>EMAIL ADDRESS</th>
                    <th>CREATED AT</th>
                    <th>STS</th>
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
        //https://datatables.yajrabox.com/eloquent/add-edit-remove-column#edit-13
        $(document).ready(function() {
            $("#datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.get') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
                        data: 'created_at',
                        name: 'created_at'
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

                ]
            });
        });
    </script>
@endpush
