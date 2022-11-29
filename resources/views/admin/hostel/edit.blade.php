@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-edit" :title="$title">
            <x-form.form action="{{ route('hostel.hostels.update', $hostel->hostel_id) }}" method="put"
                buttonName="Update Hostel" buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-6 col-sm-12" label="Hostel name" type="text" name="hostel_name"
                    placeholder="Hostel name e.g Jamhuri" value="{{ $hostel->hostel_name }}" />

                <x-form.input class="col-md-6 col-sm-12" label="Hostel Capacity" type="number" name="hostel_capacity"
                    placeholder="Hostel Capacity" value="{{ $hostel->hostel_capacity }}" />

                <x-form.input class="col-md-6 col-sm-12" label="Hostel Motto" type="text" name="hostel_motto"
                    placeholder="Hostel Motto" value="{{ $hostel->hostel_motto }}" />

                <x-form.select class="col-md-6 col-sm-12" value="{{ $hostel->id }}" label="Hostel Master"
                    name="hostel_master">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name . '-' . $user->email }}</option>
                    @endforeach
                </x-form.select>
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
