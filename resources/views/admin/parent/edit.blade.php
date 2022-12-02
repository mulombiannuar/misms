@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-user-edit" :title="$title">
            <x-form.form action="{{ route('students.parents.update', $parent->parent_id) }}" method="put"
                buttonName="Update Parent Data" buttonIcon="fa-user-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-4 col-sm-12" label="Full name" type="text" name="name" placeholder="Full name"
                    value="{{ $parent->name }}" />

                <x-form.input class="col-md-4 col-sm-12" label="Address" type="address" name="address" placeholder="Address"
                    value="{{ $parent->address }}" />

                <x-form.input class="col-md-4 col-sm-12" label="Mobile no" type="number" name="mobile_no"
                    placeholder="Mobile no" value="{{ $parent->mobile_no }}"
                    onKeyPress="if(this.value.length==10) return false;" minlength="10" maxlength="10" />

                <x-form.select class="col-md-4 col-sm-12" value="{{ $parent->gender }}" label="Gender" name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-12" value="{{ $parent->type }}" label="Type" name="type">
                    <option value="Mother">Mother</option>
                    <option value="Father">Father</option>
                    <option value="Guardian">Guardian</option>
                    <option value="Sibling">Sibling</option>
                    <option value="Others">Others</option>
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-12" label="Profession" type="text" name="profession"
                    placeholder="Profession" value="{{ $parent->profession }}" />

                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="receive_sms">Receive SMS</label>
                        <select name="receive_sms" id="receive_sms" class="form-control">
                            <option value="{{ $parent->receive_sms }}">{{ $parent->receive_sms ? 'Yes' : 'No' }}</option>
                            <option value="1">No</option>
                            <option value="0">Yes</option>
                        </select>
                    </div>
                </div>
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
