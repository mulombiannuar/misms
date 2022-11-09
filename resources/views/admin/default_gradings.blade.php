@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list-alt" :title="$title">
            <form role="form" method="post" action="{{ route('admin.default-gradings.save') }}" accept-charset="utf-8">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="grade">
                                    Grade name</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="min_score">Minimum
                                    Score</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="max_score">Maximum
                                    Score</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                            </div>
                        </div>
                    </div>
                    @for ($grade = 0; $grade < count($grades); $grade++)
                        <div class="row">
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="{{ $grades[$grade]['grade_name'] }}"
                                        value="{{ $grades[$grade]['grade_name'] }}" required disabled>
                                    <input type="hidden" name="grade_name[]" value="{{ $grades[$grade]['grade_name'] }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                                        onKeyPress="if(this.value.length==2) return false;" name="min_score[]"
                                        class="form-control" id="min_score" placeholder="Min score"
                                        value="{{ $grades[$grade]['min_score'] }}" autocomplete="on" required>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                                        onKeyPress="if(this.value.length==3) return false;" name="max_score[]"
                                        class="form-control" id="max_score" placeholder="Max score"
                                        value="{{ $grades[$grade]['max_score'] }}" autocomplete="on" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="2" placeholder="Score remarks"
                                        autocomplete="on" required>{{ $grades[$grade]['score_remarks'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                <!-- /.card-body -->

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-user-plus"></i>
                        Save Default Grading</button>

                    {{-- <button type="button" class="btn btn-default">
                        <i class="fa fa-print"></i>
                        Print Grading</button> --}}
                </div>
            </form>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
