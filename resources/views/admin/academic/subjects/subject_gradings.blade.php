@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    @foreach ($forms as $form)
                        <li class="nav-item">
                            <a class="nav-link @if ($loop->iteration == 1) {{ 'active' }} @endif"
                                id="form-{{ $form->form_numeric }}-tab" data-toggle="pill"
                                href="#form-{{ $form->form_numeric }}" role="tab"
                                aria-controls="form-{{ $form->form_numeric }}"
                                aria-selected="{{ $loop->iteration == 1 ? 'true' : 'false' }}">
                                <i class="fa fa-bars"></i> {{ strtoupper($form->form_name) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    @foreach ($forms as $form)
                        <div class="tab-pane fade @if ($loop->iteration == 1) {{ 'show active' }} @endif"
                            id="form-{{ $form->form_numeric }}" role="tabpanel"
                            aria-labelledby="form-{{ $form->form_numeric }}-tab">
                            <div class="card danger">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-2 col-sm-2">
                                            <div class="nav flex-column nav-tabs h-100" id="subject-tab" role="tablist"
                                                aria-orientation="vertical">
                                                @foreach ($subjects as $subject)
                                                    <a class="nav-link @if ($loop->iteration == 1) {{ 'active' }} @endif"
                                                        id="subject-home-tab" data-toggle="pill"
                                                        href="#subject-tab-{{ $subject->subject_id }}-form-{{ $form->form_numeric }}"
                                                        role="tab" aria-controls="subject-{{ $form->form_numeric }}"
                                                        aria-selected="<?= $loop->iteration == 1 ? 'true' : 'false' ?>">
                                                        {{ $subject->subject_name }}
                                                    </a>
                                                @endforeach

                                            </div>
                                        </div>
                                        <div class="col-10 col-sm-10">
                                            <div class="tab-content" id="subject-tabContent">

                                                @foreach ($subjects as $subject)
                                                    <div class="tab-pane text-left fade @if ($loop->iteration == 1) {{ 'active show' }} @endif"
                                                        id="subject-tab-{{ $subject->subject_id }}-form-{{ $form->form_numeric }}"
                                                        role="tabpanel"
                                                        aria-labelledby="subject-{{ $form->form_numeric }}-tab">
                                                        <div class="card card-secondary">
                                                            <div class="card-header">
                                                                <h3 class="card-title">
                                                                    <i class="fa fa-graduation-cap"></i>
                                                                    {{ strtoupper($subject->subject_name . ' - ' . $form->form_name) }}
                                                                </h3>
                                                            </div>
                                                            <div class="card-body">
                                                                @if (!empty(count($form->subjectGrades($subject->subject_id))))
                                                                    <form role="form" method="post"
                                                                        action="{{ route('admin.subject-grading.store') }}"
                                                                        accept-charset="utf-8">
                                                                        @csrf
                                                                        <input type="hidden" name="subject_id"
                                                                            value="{{ $subject->subject_id }}">
                                                                        <input type="hidden" name="form_numeric"
                                                                            value="{{ $form->form_numeric }}">
                                                                        <div class="card-body">
                                                                            @foreach ($form->subjectGrades($subject->subject_id) as $grade)
                                                                                <div class="row">
                                                                                    <div class="col-md-2 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration == 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">
                                                                                                    Grade</label>
                                                                                            @endif
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                value="{{ $grade->grade_name }}"
                                                                                                required disabled>
                                                                                            <input type="hidden"
                                                                                                name="grade_name[]"
                                                                                                value="{{ $grade->grade_name }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration == 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">Minimum
                                                                                                    Score</label>
                                                                                            @endif
                                                                                            <input type="number"
                                                                                                step="0.01"
                                                                                                pattern="^\d+(?:\.\d{1,2})?$"
                                                                                                onKeyPress="if(this.value.length==5) return false;"
                                                                                                name="min_score[]"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                placeholder="Min score"
                                                                                                autocomplete="on"
                                                                                                value="{{ $grade->min_score }}"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration == 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">Maximum
                                                                                                    Score</label>
                                                                                            @endif
                                                                                            <input type="number"
                                                                                                step="0.01"
                                                                                                pattern="^\d+(?:\.\d{1,2})?$"
                                                                                                onKeyPress="if(this.value.length==3) return false;"
                                                                                                name="max_score[]"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                placeholder="Max score"
                                                                                                autocomplete="on"
                                                                                                value="{{ $grade->max_score }}"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration == 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">Remarks</label>
                                                                                            @endif
                                                                                            <input type="text"
                                                                                                name="score_remarks[]"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                placeholder="Remarks"
                                                                                                autocomplete="on"
                                                                                                value="{{ $grade->score_remarks }}"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <!-- /.card-body -->

                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="submit" class="btn btn-info">
                                                                                <i class="fa fa-edit"></i>
                                                                                Update
                                                                                {{ ucwords($subject->subject_name . ' - ' . $form->form_name) }}
                                                                                Grading</button>

                                                                            <button type="button"
                                                                                class="btn btn-default">
                                                                                <i class="fa fa-print"></i>
                                                                                Print
                                                                                {{ ucwords($subject->subject_name . ' - ' . $form->form_name) }}
                                                                                Grading</button>
                                                                        </div>
                                                                    </form>
                                                                @else
                                                                    <form role="form" method="post"
                                                                        action="{{ route('admin.subject-grading.store') }}"
                                                                        accept-charset="utf-8">
                                                                        @csrf
                                                                        <input type="hidden" name="subject_id"
                                                                            value="{{ $subject->subject_id }}">
                                                                        <input type="hidden" name="form_numeric"
                                                                            value="{{ $form->form_numeric }}">
                                                                        <div class="card-body">
                                                                            @foreach ($grades as $grade)
                                                                                <div class="row">
                                                                                    <div class="col-md-2 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration === 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">
                                                                                                    Grade</label>
                                                                                            @endif
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                value="{{ $grade->grade_name }}"
                                                                                                required disabled>
                                                                                            <input type="hidden"
                                                                                                name="grade_name[]"
                                                                                                value="{{ $grade->grade_name }}">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-2 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration === 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">Minimum
                                                                                                    Score</label>
                                                                                            @endif
                                                                                            <input type="number"
                                                                                                step="0.01"
                                                                                                pattern="^\d+(?:\.\d{1,2})?$"
                                                                                                onKeyPress="if(this.value.length==5) return false;"
                                                                                                name="min_score[]"
                                                                                                value="{{ $grade->min_score }}"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                placeholder="Min score"
                                                                                                autocomplete="on" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration === 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">Maximum
                                                                                                    Score</label>
                                                                                            @endif
                                                                                            <input type="number"
                                                                                                step="0.01"
                                                                                                pattern="^\d+(?:\.\d{1,2})?$"
                                                                                                onKeyPress="if(this.value.length==3) return false;"
                                                                                                name="max_score[]"
                                                                                                value="{{ $grade->max_score }}"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                placeholder="Max score"
                                                                                                autocomplete="on" required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="form-group">
                                                                                            @if ($loop->iteration === 1)
                                                                                                <label
                                                                                                    for="exampleInputText1">Remarks</label>
                                                                                            @endif
                                                                                            <input type="text"
                                                                                                name="score_remarks[]"
                                                                                                value="{{ $grade->score_remarks }}"
                                                                                                class="form-control"
                                                                                                id="exampleInputText1"
                                                                                                placeholder="Remarks"
                                                                                                autocomplete="on" required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <!-- /.card-body -->

                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">
                                                                                <i class="fa fa-user-plus"></i>
                                                                                Save
                                                                                {{ ucwords($subject->subject_name . ' - ' . $form->form_name) }}
                                                                                Grading</button>

                                                                            <button type="button"
                                                                                class="btn btn-default">
                                                                                <i class="fa fa-print"></i>
                                                                                Print Grading</button>
                                                                        </div>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- /.card -->
        </div>
    </x-section>
    <!-- /.section component -->
@endsection
