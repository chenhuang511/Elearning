<div class="col-md-6">
    <section class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_name'] }}</label>
                <div class="col-md-8"><input type="text" name="name[]" value="{{ object.name }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_name'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_comprehension'] }}</label>
                <div class="col-md-2"><input type="text" name="comprehension[]" value="{{ object.comprehension }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_comprehension'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_comprehension[]" value="{{ object.note_comprehension }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_comprehension'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_fluency'] }}</label>
                <div class="col-md-2"><input type="text" name="fluency[]" value="{{ object.fluency }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_fluency'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_fluency[]" value="{{ object.note_fluency }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_fluency'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_grammar'] }}</label>
                <div class="col-md-2"><input type="text" name="grammar[]" value="{{ object.grammar }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_grammar'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_grammar[]" value="{{ object.note_grammar }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_grammar'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_vocabular'] }}</label>
                <div class="col-md-2"><input type="text" name="vocabular[]" value="{{ object.vocabular }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_vocabular'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_vocabulary[]" value="{{ object.note_vocabulary }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_vocabular'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_pronunciation'] }}</label>
                <div class="col-md-2"><input type="text" name="pronunciation[]" value="{{ object.pronunciation }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_pronunciation'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_pronunciation[]" value="{{ object.note_pronunciation }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_pronunciation'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_datetest'] }}</label>
                <div class="col-md-8"><input type="text" name="datetest[]" value="{{ date("d-m-Y",object.datetest) }}" class="form-control" placeholder="dd-mm-yyyy" required></div>
            </div>
            <div class="text-right"><a href="javascript:void(0)" onclick="$(this).parent().parent().parent().remove()">{{ labelkey['general.btn_delete'] }}</a></div>
        </div>
    </section>

</div>
