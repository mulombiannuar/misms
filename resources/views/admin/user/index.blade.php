@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-users" title=" Manage User ({{ count($users) }})">
            <div class="text-right">
                <a href="{{ route('admin.users.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New User" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>NAMES</th>
                    <th>GENDER</th>
                    <th>EMAIL ADDRESS</th>
                    <th>MOBILE NUMBER</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ Str::ucfirst($user->gender) }}</td>
                            <td><strong>{{ $user->email }}</strong></td>
                            <td><strong>{{ $user->mobile_no }}</strong></td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}">
                                    <x-buttons.button class="btn-primary btn-xs" buttonName="Edit" buttonIcon="fa-edit" />
                                </a>
                                <a href="{{ route('admin.users.show', $user->id) }}">
                                    <x-buttons.button class="btn-info btn-xs" buttonName="Show" buttonIcon="fa-bars" />
                                </a>
                                <x-buttons.delete action="{{ route('admin.users.destroy', $user->id) }}" btnSize="btn-xs" />
                                @if ($user->status == 0)
                                    <x-buttons.activate action="{{ route('admin.users.activate', $user->id) }}"
                                        btnSize="btn-xs" />
                                @else
                                    <x-buttons.deactivate action="{{ route('admin.users.deactivate', $user->id) }}"
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
