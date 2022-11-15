@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <div class="text-right">
            <a href="{{ route('admin.users.create') }}">
                <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New User" buttonIcon="fa-plus" />
            </a>
        </div>
        <x-card class="card-primary" icon="fa-users" title="Manage Users">
            <x-table.table id="datatable">
                <x-table.thead>
                    <th>S.N</th>
                    <th>NAMES</th>
                    <th>EMAIL ADDRESS</th>
                    <th>CREATED AT</th>
                    <th>UPDATED AT</th>
                    <th>ACTION</th>
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
                        data: 'id',
                        name: 'id'
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
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
