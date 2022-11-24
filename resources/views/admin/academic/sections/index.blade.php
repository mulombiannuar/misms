@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" :title="$title">
            <div class="text-right">
                <a href="{{ route('admin.sections.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Section" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>C.N</th>
                    <th>TEACHER</th>
                    <th>NAMES</th>
                    <th>SECTION</th>
                    <th>CREATED AT</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($sections as $section)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucwords($section->teacher->name) }}</td>
                            <td>{{ $section->form->form_name }}</td>
                            <td>{{ $section->section_name }}</td>
                            <td>{{ $section->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.sections.edit', $section->section_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-info" buttonName="Edit" buttonIcon="fa-edit" />
                                </a>
                                <x-buttons.delete action="{{ route('admin.sections.destroy', $section->section_id) }}"
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
