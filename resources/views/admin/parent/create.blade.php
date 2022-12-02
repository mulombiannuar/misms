@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-user-plus" :title="$title">
            <x-form.form action="{{ route('students.parents.store') }}" method="post" buttonName="Add New Parent"
                buttonIcon="fa-user-plus" buttonClass="btn-secondary">
                <input type="hidden" name="has_student" value="0">

                <x-form.input class="col-md-4 col-sm-12" label="Full name" type="text" name="name" placeholder="Full name"
                    value="" />

                <x-form.input class="col-md-4 col-sm-12" label="Address" type="address" name="address" placeholder="Address"
                    value="" />

                <x-form.input class="col-md-4 col-sm-12" label="Mobile no" type="number" name="mobile_no"
                    placeholder="Mobile no" value="" onKeyPress="if(this.value.length==10) return false;"
                    minlength="10" maxlength="10" />

                <x-form.select class="col-md-4 col-sm-12" value="" label="Gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-12" value="" label="Type" name="type">
                    <option value="Mother">Mother</option>
                    <option value="Father">Father</option>
                    <option value="Guardian">Guardian</option>
                    <option value="Sibling">Sibling</option>
                    <option value="Others">Others</option>
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-12" label="Profession" type="text" name="profession"
                    placeholder="Profession" value="" />

                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="receive_sms">Receive SMS</label>
                        <select name="receive_sms" id="receive_sms" class="form-control">
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
