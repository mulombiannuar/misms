@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-calendar" :title="$title">
            <div class="text-right">
                <a href="{{ route('admin.sessions.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Session" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>Session</th>
                    <th>Term</th>
                    <th>Opening Date</th>
                    <th>Closing Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </x-table.thead>
                <tbody>
                    @foreach ($sessions as $session)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $session->session }}</strong></td>
                            <td>Term {{ $session->term }}</td>
                            <td><strong>{{ date_format(date_create($session->opening_date), 'D, d M Y') }}</strong></td>
                            <td><strong>{{ date_format(date_create($session->closing_date), 'D, d M Y') }}</strong></td>
                            <td>{{ $session->status == 1 ? 'Active' : 'Not Active' }}</td>
                            <td>
                                <a href="{{ route('admin.sessions.edit', $session->session_id) }}">
                                    <x-buttons.button class="btn-primary btn-xs" buttonName="Edit" buttonIcon="fa-edit" />
                                </a>
                                <a href="{{ route('admin.sessions.show', $session->session_id) }}">
                                    <x-buttons.button class="btn-info btn-xs" buttonName="Show" buttonIcon="fa-bars" />
                                </a>
                                <x-buttons.delete action="{{ route('admin.sessions.destroy', $session->session_id) }}"
                                    btnSize="btn-xs" />
                                @if ($session->status == 0)
                                    <x-buttons.activate
                                        action="{{ route('admin.sessions.activate', $session->session_id) }}"
                                        btnSize="btn-xs" />
                                @else
                                    <x-buttons.deactivate
                                        action="{{ route('admin.sessions.deactivate', $session->session_id) }}"
                                        btnSize="btn-xs" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
