<div class="col-md-6">
    <section class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_name'] }}</label>
                <div class="col-md-8"><input type="text" name="name[]" value="{{ object.name }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_name'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_visual_aids'] }}</label>
                <div class="col-md-8"><input type="text" name="visual_aids[]" value="{{ object.visual_aids }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_visual_aids'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_body_language'] }}</label>
                <div class="col-md-8"><input type="text" name="body_language[]" value="{{ object.body_language }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_body_language'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_voice'] }}</label>
                <div class="col-md-8"><input type="text" name="voice[]" value="{{ object.voice }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_voice'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_interaction'] }}</label>
                <div class="col-md-8"><input type="text" name="interaction[]" value="{{ object.interaction }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_interaction'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_pronunciation'] }}</label>
                <div class="col-md-8"><input type="text" name="pronunciation[]" value="{{ object.pronunciation }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_pronunciation'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_language_use'] }}</label>
                <div class="col-md-8"><input type="text" name="language_use[]" value="{{ object.language_use }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_language_use'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_organization'] }}</label>
                <div class="col-md-8"><input type="text" name="organization[]" value="{{ object.organization }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_organization'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_datetest'] }}</label>
                <div class="col-md-8"><input type="text" name="datetest[]" value="{{ date("d-m-Y",object.datetest) }}" class="form-control" placeholder="dd-mm-yyyy" required></div>
            </div>
            <div class="text-right"><a href="javascript:void(0)" onclick="$(this).parent().parent().parent().remove()">{{ labelkey['general.btn_delete'] }}</a></div>
        </div>
    </section>

</div>
