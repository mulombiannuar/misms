@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-edit" :title="$title">
            <x-form.form action="{{ route('admin.exams.update', $exam->exam_id) }}" method="put"
                buttonName="Update Exam Data" buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-3 col-sm-12" label="Exam name" type="text" name="name"
                    placeholder="Exam name e.g C.A.T 1 Midterm 2022" value="{{ $exam->name }}" />

                <x-form.input class="col-md-3 col-sm-12" label="Start Date" type="date" name="start_date"
                    placeholder="Select date start date"
                    value="{{ date_format(date_create($exam->start_date), 'Y-m-d') }}" />

                <x-form.input class="col-md-3 col-sm-6" label="End Date" type="date" name="end_date"
                    placeholder="Selext end date" value="{{ date_format(date_create($exam->end_date), 'Y-m-d') }}" />

                <x-form.input class="col-md-3 col-sm-12" label="Deadline Date" type="date" name="deadline_date"
                    placeholder="Select deadline date"
                    value="{{ date_format(date_create($exam->deadline_date), 'Y-m-d') }}" />

                <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->class_numeric }}" label="Class"
                    name="class_numeric">
                    @foreach ($forms as $form)
                        <option value="{{ $form->form_numeric }}">
                            {{ $form->form_name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->term }}" label="Term" name="term">
                    <option value="1">Term 1</option>
                    <option value="2">Term 2</option>
                    <option value="3">Term 3</option>
                </x-form.select>

                <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->converted }}" label="Exam Converted?"
                    name="converted">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </x-form.select>

                <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->conversion }}" label="Exam Conversion"
                    name="conversion">
                    <option value="">Select Conversion</option>
                    <option value="0.15">15%</option>
                    <option value="0.30">30%</option>
                    <option value="0.50">50%</option>
                    <option value="0.70">70%</option>
                    <option value="1.0">100%</option>
                </x-form.select>

                <x-form.textarea class="col-sm-12" label="Extra information" name="notes"
                    placeholder="Enter exam extra information" value="{{ $exam->notes }}" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
