@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-plus-circle" :title="$title">
            <x-form.form action="{{ route('attendances.class-attendances.store') }}" method="post"
                buttonName="Save New Attendance" buttonIcon="fa-plus-circle" buttonClass="btn-secondary">

                <x-form.input class="col-md-4 col-sm-6" label="Attendance Date" type="date" name="date"
                    placeholder="Select attendancce date" value="{{ date_format(date_create(now()), 'Y-m-d') }}" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="Class" name="section_numeric">
                    @foreach ($forms as $form)
                        <option value="{{ $form->form_numeric }}">
                            {{ $form->form_name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-6" value="" label="Sections" name="section">
                    <option value="">
                        Slect class numeric first
                    </option>
                </x-form.select>
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#section_numeric').change(function() {
                section_numeric = $('#section_numeric').val();
                if (section_numeric != '') {
                    $.ajax({
                        url: "{{ route('get.formsections') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            section_numeric: section_numeric
                        },
                        success: function(data) {
                            console.log(data);
                            $('#section').html(data);
                        },
                        error: function(xhr, desc, err) {
                            console.log(xhr);
                            //console.log("Details0: " + desc + "\nError:" + err);
                        },
                    });
                } else {
                    $('#section').html('<option value="">Select Form First</option>');
                }
            });
        });
    </script>
@endpush
