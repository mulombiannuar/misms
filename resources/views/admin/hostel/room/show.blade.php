@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-users" :title="$title">
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>ADM NO.</th>
                    <th>STUDENT NAME</th>
                    <th>BED SPACE LABEL</th>
                    <th>Action</th>
                </x-table.thead>
                <tbody>

                </tbody>
            </x-table.table>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
