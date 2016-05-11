<div class="col-md-6">
    <section class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_month'] }}</label>
                <div class="col-md-8"><input type="text" name="month[]" value="{{ object.month }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_month'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_attendance'] }}</label>
                <div class="col-md-8"><input type="text" name="attendance[]" value="{{ object.attendance }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_attendance'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_participation'] }}</label>
                <div class="col-md-8"><input type="text" name="participation[]" value="{{ object.participation }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_participation'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_behavior'] }}</label>
                <div class="col-md-8"><input type="text" name="behavior[]" value="{{ object.behavior }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_behavior'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_diligence'] }}</label>
                <div class="col-md-8"><input type="text" name="diligence[]" value="{{ object.diligence }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_diligence'] }}" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_datetest'] }}</label>
                <div class="col-md-8"><input type="text" name="datetest[]" value="{{ date("d-m-Y",object.datetest) }}" class="form-control" placeholder="dd-mm-yyyy" required></div>
            </div>
            <div class="text-right"><a href="javascript:void(0)" onclick="$(this).parent().parent().parent().remove()">{{ labelkey['general.btn_delete'] }}</a></div>
        </div>
    </section>

</div>
