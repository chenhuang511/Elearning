<?php echo $header; ?>

<form method="post" class="form-horizontal" action="<?php echo Uri::to('admin/curriculum/add/course'); ?>"
      enctype="multipart/form-data" novalidate>
    <input name="token" type="hidden" value="<?php echo $token; ?>">
    <div class="form-group notification">
        <?php
        if (count($errors) == 0) {
            echo $messages;
        }
        ?>
    </div>
    <div class="form-group <?php if (isset($errors['fullname'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="fullname" class="col-sm-2 control-label"><?php echo __('courses.fullname') ?> <span
                class="text-danger">*</span></label>
        <div class="col-sm-10">
            <?php echo Form::text('fullname', Input::previous('fullname'), array(
                'placeholder' => __('courses.fullname'),
                'autocomplete' => 'off',
                'autofocus' => 'true',
                'class' => 'form-control'
            )); ?>
            <?php if (isset($errors['fullname'])) { ?>
                <p class="help-block"><?php echo $errors['fullname'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="form-group <?php if (isset($errors['shortname'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="shortname" class="col-sm-2 control-label"><?php echo __('courses.shortname') ?> <span
                class="text-danger">*</span></label>
        <div class="col-sm-4">
            <?php echo Form::text('shortname', Input::previous('shortname'), array(
                'placeholder' => __('courses.shortname'),
                'autocomplete' => 'off',
                'autofocus' => 'true',
                'class' => 'form-control'
            )); ?>
            <?php if (isset($errors['shortname'])) { ?>
                <p class="help-block"><?php echo $errors['shortname'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php if (isset($errors['startdate'])) {
                echo 'has-error';
            } else {
                echo '';
            } ?>">
                <label for="startdate"
                       class="col-sm-4 control-label"><?php echo __('courses.startdate') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <div class='input-group date' id='datetimepicker_startdate'>
                        <input id="startdate" name="startdate" type='text' class="form-control" readonly/>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </span>
                    </div>
                    <?php if (isset($errors['startdate'])) { ?>
                        <p class="help-block"><?php echo $errors['startdate'][0] ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php if (isset($errors['enddate'])) {
                echo 'has-error';
            } else {
                echo '';
            } ?>">
                <label for="datetimepicker_enddate"
                       class="col-sm-4 control-label"><?php echo __('courses.enddate') ?> <span
                        class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <div class='input-group date' id='datetimepicker_enddate'>
                        <input id="enddate" name="enddate" type='text' class="form-control" readonly/>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </span>
                    </div>
                    <?php if (isset($errors['enddate'])) { ?>
                        <p class="help-block"><?php echo $errors['enddate'][0] ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group <?php if (isset($errors['summary'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="summary" class="col-sm-2 control-label"><?php echo __('courses.summary') ?></label>
        <div class="col-sm-10">
            <?php echo Form::textarea('summary', Input::previous('summary'), array('id' => 'summary', 'class' => 'form-control')); ?>
            <em><?php echo __('courses.summary_explain'); ?></em>
            <?php if (isset($errors['summary'])) { ?>
                <p class="help-block"><?php echo $errors['summary'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="form-group text-right">
        <aside class="buttons">
            <?php echo Form::button(__('global.continue'), array(
                'type' => 'submit',
                'class' => 'btn btn-primary btn-continue'
            )); ?>
            <?php echo Html::link('admin/posts', __('global.cancel'), array(
                'class' => 'btn btn-danger btn-cancel'
            )); ?>
        </aside>
    </div>
</form>
<script src="<?php echo asset('anchor/views/assets/js/bootstrap-datetimepicker.js'); ?>"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker_startdate').datetimepicker({
            language: 'fr',
            startDate: new Date(),
            startView: 2,
            minView: 2,
            format: 'dd/mm/yyyy',
            pickTime: false,
        });
        $('#datetimepicker_enddate').datetimepicker({
            language: 'fr',
            pickTime: false,
            startView: 2,
            minView: 2,
            format: 'dd/mm/yyyy'
        });

        $("#datetimepicker_startdate").on("changeDate", function (e) {
            $('#datetimepicker_enddate').datetimepicker('setStartDate', e.date);
        });
        $("#datetimepicker_enddate").on("changeDate", function (e) {
            $('#datetimepicker_startdate').datetimepicker('setEndDate', e.date);
        });
    });
</script>
<script>

</script>
<?php echo $footer; ?>
