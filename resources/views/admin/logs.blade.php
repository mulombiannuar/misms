@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" title="{{ $title }} ({{ $logs->count() }})">
            <x-table.table id="datatable">
                <x-table.thead>
                    <th>S.N</th>
                    <th>LOGGED DATE</th>
                    <th>USERNAME</th>
                    <th>EMAIL</th>
                    <th>ACTIVITY TYPE</th>
                    <th>DESCRIPTION</th>
                    <th>IP ADDRESS</th>
                </x-table.thead>
                <tbody>

                </tbody>
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
                ajax: "{{ route('admin.get-logs') }}",
                columns: [{
                        data: 'log_id',
                        name: 'log_id'
                    },
                    {
                        data: 'date',
                        name: 'date',
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
                        data: 'activity_type',
                        name: 'activity_type'
                    },
                    {
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address',
                    },

                ]
            });
        });
    </script>
@endpush
