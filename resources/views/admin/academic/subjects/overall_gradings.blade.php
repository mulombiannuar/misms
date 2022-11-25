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
                                id="custom-tabs-two-1-tab" data-toggle="pill"
                                href="#custom-tabs-form-{{ $form->form_numeric }}" role="tab"
                                aria-controls="custom-tabs-{{ $form->form_numeric }}"
                                aria-selected="<?= $loop->iteration == 1 ? 'true' : 'false' ?>">
                                <i class="fa fa-bars"></i> {{ strtoupper($form->form_name) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    @foreach ($forms as $form)
                        <div class="tab-pane fade @if ($loop->iteration == 1) {{ 'active show' }} @endif"
                            id="custom-tabs-form-{{ $form->form_numeric }}" role="tabpanel"
                            aria-labelledby="custom-tabs-{{ $form->form_numeric }}-tab">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fa fa-graduation-cap"></i>
                                        {{ strtoupper($form->form_name) }} OVERALL GRADING
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if (!empty(count($form->overallGradings($form->form_numeric))))
                                        <form role="form" method="post"
                                            action="{{ route('admin.overall-grading.store') }}" accept-charset="utf-8">
                                            @csrf
                                            <input type="hidden" name="form_numeric" value="{{ $form->form_numeric }}">
                                            <div class="card-body">
                                                @foreach ($form->overallGradings($form->form_numeric) as $grade)
                                                    <div class="row">
                                                        <div class="col-md-1 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration == 1)
                                                                    <label for="exampleInputText1">
                                                                        Grade</label>
                                                                @endif
                                                                <input type="text" class="form-control"
                                                                    id="exampleInputText1" value="{{ $grade->grade_name }}"
                                                                    required disabled>
                                                                <input type="hidden" name="grade_name[]"
                                                                    value="{{ $grade->grade_name }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration == 1)
                                                                    <label for="exampleInputText1">Minimum
                                                                        Score</label>
                                                                @endif
                                                                <input type="number" step="0.01"
                                                                    pattern="^\d+(?:\.\d{1,2})?$"
                                                                    onKeyPress="if(this.value.length==5) return false;"
                                                                    name="min_score[]" class="form-control"
                                                                    id="exampleInputText1" placeholder="Min score"
                                                                    autocomplete="on" value="{{ $grade->min_score }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration == 1)
                                                                    <label for="exampleInputText1">Maximum
                                                                        Score</label>
                                                                @endif
                                                                <input type="number" step="0.01"
                                                                    pattern="^\d+(?:\.\d{1,2})?$"
                                                                    onKeyPress="if(this.value.length==3) return false;"
                                                                    name="max_score[]" class="form-control"
                                                                    id="exampleInputText1" placeholder="Max score"
                                                                    autocomplete="on" value="{{ $grade->max_score }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration == 1)
                                                                    <label for="exampleInputText1">Class Teacher</label>
                                                                @endif
                                                                <textarea name="score_remarks[]" class="form-control" cols="10" rows="2" placeholder="Class teacher Remarks"
                                                                    autocomplete="on" required>{{ $grade->score_remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration == 1)
                                                                    <label for="exampleInputText1">Head Teacher</label>
                                                                @endif
                                                                <textarea name="principal_remarks[]" class="form-control" cols="10" rows="2"
                                                                    placeholder="Enter headteacher remarks" autocomplete="on" required>{{ $grade->principal_remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- /.card-body -->

                                            <div class="modal-footer justify-content-between">
                                                <button type="submit" class="btn btn-info">
                                                    <i class="fa fa-edit"></i>
                                                    Update Grading</button>

                                                <button type="button" class="btn btn-default">
                                                    <i class="fa fa-print"></i>
                                                    Print Grading</button>
                                            </div>
                                        </form>
                                    @else
                                        <form role="form" method="post"
                                            action="{{ route('admin.overall-grading.store') }}" accept-charset="utf-8">
                                            @csrf
                                            <input type="hidden" name="form_numeric" value="{{ $form->form_numeric }}">
                                            <div class="card-body">
                                                @foreach ($grades as $grade)
                                                    <div class="row">
                                                        <div class="col-md-1 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration === 1)
                                                                    <label for="exampleInputText1">
                                                                        Grade</label>
                                                                @endif
                                                                <input type="text" class="form-control"
                                                                    id="exampleInputText1"
                                                                    value="{{ $grade->grade_name }}" required disabled>
                                                                <input type="hidden" name="grade_name[]"
                                                                    value="{{ $grade->grade_name }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration === 1)
                                                                    <label for="exampleInputText1">Min
                                                                    </label>
                                                                @endif
                                                                <input type="number" step="0.01"
                                                                    pattern="^\d+(?:\.\d{1,2})?$"
                                                                    onKeyPress="if(this.value.length==5) return false;"
                                                                    name="min_score[]" class="form-control"
                                                                    value="{{ $grade->min_score }}"
                                                                    id="exampleInputText1" placeholder="Min score"
                                                                    autocomplete="on" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration === 1)
                                                                    <label for="exampleInputText1">Max</label>
                                                                @endif
                                                                <input type="number" step="0.01"
                                                                    pattern="^\d+(?:\.\d{1,2})?$"
                                                                    onKeyPress="if(this.value.length==3) return false;"
                                                                    value="{{ $grade->max_score }}" name="max_score[]"
                                                                    class="form-control" id="exampleInputText1"
                                                                    placeholder="Max score" autocomplete="on" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration == 1)
                                                                    <label for="exampleInputText1">Class Teacher</label>
                                                                @endif
                                                                <textarea name="score_remarks[]" class="form-control" cols="10" rows="2"
                                                                    placeholder="Class teacher Remarks" autocomplete="on" required>{{ $grade->score_remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 col-sm-12">
                                                            <div class="form-group">
                                                                @if ($loop->iteration == 1)
                                                                    <label for="exampleInputText1">Head Teacher</label>
                                                                @endif
                                                                <textarea name="principal_remarks[]" class="form-control" cols="10" rows="2"
                                                                    placeholder="Enter headteacher remarks" autocomplete="on" required>{{ $grade->score_remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- /.card-body -->

                                            <div class="modal-footer justify-content-between">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-user-plus"></i>
                                                    Save {{ $form->form_name }} Grading</button>

                                                <button type="button" class="btn btn-default">
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
        <!-- /.card -->
    </x-section>
    <!-- /.section component -->
@endsection
