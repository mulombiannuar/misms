<form role="form" method="post" action="{{ route('admin.overall-grading.store') }}" accept-charset="utf-8">
    @csrf
    <input type="hidden" name="form_numeric" value="{{ $form->form_numeric }}">
    <div class="card-body">

        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">
                        Grade</label>
                    <input type="text" class="form-control" id="exampleInputText1" value="A" required disabled>
                    <input type="hidden" name="grade_name[]" value="A">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Minimum
                        Score</label>
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Maximum
                        Score</label>
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==3) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4" placeholder="Class teacher Remarks"
                        autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="A-" required disabled>
                    <input type="hidden" name="grade_name[]" value="A-">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4" placeholder="Class teacher Remarks"
                        autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="B+" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="B+">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="B" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="B">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="B-" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="B-">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="C+" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="C+">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="C" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="C">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="C-" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="C-">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="D+" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="D+">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="D" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="D">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="D-" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="D-">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputText1" value="E" required
                        disabled>
                    <input type="hidden" name="grade_name[]" value="E">
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="min_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Min score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <input type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        onKeyPress="if(this.value.length==5) return false;" name="max_score[]" class="form-control"
                        id="exampleInputText1" placeholder="Max score" autocomplete="on" required>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Class Teacher</label>
                    <textarea name="score_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Class teacher Remarks" autocomplete="on" required></textarea>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="exampleInputText1">Head Teacher</label>
                    <textarea name="principal_remarks[]" class="form-control" cols="10" rows="4"
                        placeholder="Enter headteacher remarks" autocomplete="on" required></textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->

    <div class="modal-footer justify-content-between">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-user-plus"></i>
            Save Grading</button>

        <button type="button" class="btn btn-default">
            <i class="fa fa-print"></i>
            Print Grading</button>
    </div>
</form>
