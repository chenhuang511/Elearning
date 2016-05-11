<div class="col-md-6">
    <section class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_name'] }}</label>
                <div class="col-md-8"><input type="text" name="name[]" value="{{ object.name }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_name'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_reading'] }}</label>
                <div class="col-md-2"><input type="text" name="reading[]" value="{{ object.reading }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_reading'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_reading[]" value="{{ object.note_reading }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_reading'] }}"></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_listening'] }}</label>
                <div class="col-md-2"><input type="text" name="listening[]" value="{{ object.listening }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_listening'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_listening[]" value="{{ object.note_listening }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_listening'] }}"></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_writing'] }}</label>
                <div class="col-md-2"><input type="text" name="writing[]" value="{{ object.writing }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_writing'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_writing[]" value="{{ object.note_writing }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_writing'] }}"></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_grammar'] }}</label>
                <div class="col-md-2"><input type="text" name="grammar[]" value="{{ object.grammar }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_grammar'] }}" required></div>
                <div class="col-md-6"><input type="text" name="note_grammar[]" value="{{ object.note_grammar }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_grammar'] }}"></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_datetest'] }}</label>
                <div class="col-md-8"><input type="text" name="datetest[]" value="{{ date("d-m-Y",object.datetest) }}" class="form-control" placeholder="dd-mm-yyyy" required></div>
            </div>

            <div class="text-right"><a href="javascript:void(0)" onclick="$(this).parent().parent().parent().remove()">{{ labelkey['general.btn_delete'] }}</a></div>
        </div>
    </section>

</div>
