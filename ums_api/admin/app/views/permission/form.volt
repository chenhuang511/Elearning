<a href="/permission/index" class="btn btn-sm btn-success addon-btn m-b-10"><i class="zmdi zmdi-arrow-left"></i> {{ labelkey['general.lbl_back'] }}</a>
<div class="row">
    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    {{ labelkey['permission.lbl_permissioninfo'] }}
                </header>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="key">{{ labelkey['permission.lbl_key'] }}</label>
                        <div class="col-md-10"><input type="text" name="key" value="{{ object.key }}" class="form-control" id="key" placeholder="{{ labelkey['permission.plh_key'] }}" required></div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="note">{{ labelkey['permission.lbl_note'] }}</label>
                        <div class="col-md-10"><input type="text" name="note" value="{{ object.note }}" class="form-control" id="note" placeholder="{{ labelkey['permission.plh_note'] }}"> </input> </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="status">{{ labelkey['permission.lbl_status'] }}</label>
                        <div class="col-md-10"><input type="checkbox" name="status" value="1" class="js-switch-blue" id="status" {% if object.status == 1 %} checked {% endif %}></div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-lg-12">
            <button type="submit" class="btn btn-info">{{ labelkey['general.btn_save'] }}</button>
        </div>
    </form>
</div>

<!--bootstrap picker-->
<script type="text/javascript" src="/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--picker initialization-->
<script src="/js/picker-init.js"></script>