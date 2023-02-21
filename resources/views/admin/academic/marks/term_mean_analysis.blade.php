@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-secondary" icon="fa-edit" :title="$title">
            <form role="form" action="{{ route('marks.scores.term_analysed') }}">
                @csrf
                <div class="row">
                    <x-form.select class="col-md-4 col-sm-12" value="" label="Class" name="class">
                        @foreach ($forms as $form)
                            <option value="{{ $form->form_numeric }}">
                                {{ $form->form_name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select class="col-md-4 col-sm-12" value="" label="Year" name="year">
                        <option value="">- Select Year -</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->year }}">{{ $year->year }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select class="col-md-4 col-sm-12" value="" label="Term" name="term">
                        <option value="">- Select Term -</option>
                        <option value="1">Term 1</option>
                        <option value="2">Term 2</option>
                        <option value="3">Term 3</option>
                    </x-form.select>
                    <!-- /.card-body -->
                    <div class="modal-footer justify-content-between col-sm-12">
                        <button type="submit" class="btn btn-secondary"> <i class="fa fa-calendar"></i>
                            View Analysed Report</button>
                    </div>
                </div>
            </form>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
