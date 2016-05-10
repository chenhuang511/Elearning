<div class="col-md-6">
    <section class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_datetest'] }}</label>
                <div class="col-md-8"><input type="text" name="datetest[]" value="{{ date("d-m-Y",object.datetest) }}" class="form-control" placeholder="dd-mm-yyyy" required></div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">{{ labelkey['reportpupil.lbl_point'] }}</label>
                <div class="col-md-8"><input type="text" name="point[]" value="{{ object.point }}" class="form-control" placeholder="{{ labelkey['reportpupil.lbl_point'] }}" required></div>
            </div>

            <div class="text-right"><a href="javascript:void(0)" onclick="$(this).parent().parent().parent().remove()">{{ labelkey['general.btn_delete'] }}</a></div>
        </div>
    </section>

</div>
