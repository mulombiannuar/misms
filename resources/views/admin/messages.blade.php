@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-envelope" :title="$title">
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>LOGGED DATE</th>
                    <th>RECIPIENT</th>
                    <th width="40%">MESSAGE BODY</th>
                    <th>MOBILE NUMBER</th>
                    <th>TYPE</th>
                </x-table.thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $message->logged_date }}</td>
                            <td>{{ $message->recipient_name }}</td>
                            <td>{{ $message->message_body }}</td>
                            <td>{{ $message->recipient_no }}</td>
                            <td>{{ $message->message_type }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
