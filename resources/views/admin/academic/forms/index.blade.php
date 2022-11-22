@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" :title="$title">
            <div class="text-right">
                <a href="{{ route('admin.forms.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Class" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>C.N</th>
                    <th>NUMERIC</th>
                    <th>NAMES</th>
                    <th>MIN SUBS</th>
                    <th>MAX SUBS</th>
                    <th>CREATED AT</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($forms as $form)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $form->form_numeric }}</td>
                            <td>{{ $form->form_name }}</td>
                            <td>{{ $form->min_subs }}</td>
                            <td>{{ $form->max_subs }}</td>
                            <td>{{ $form->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.forms.edit', $form->form_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-info" buttonName="Edit" buttonIcon="fa-edit" />
                                </a>
                                <x-buttons.delete action="{{ route('admin.forms.destroy', $form->form_id) }}"
                                    btnSize="btn-xs" />
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
