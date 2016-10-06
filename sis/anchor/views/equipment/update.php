<?php echo $header; ?>

<form method="post" class="form-horizontal" action="<?php echo Uri::to('admin/equipment/update/room/' . $room->id); ?>"
      enctype="multipart/form-data" novalidate>
    <input name="token" type="hidden" value="<?php echo $token; ?>">
    <div class="form-group">
        <h4 class="step-heading">Cập nhật thông tin phòng học</h4>
    </div>
    <div class="form-group notification">
        <?php
        if (count($errors) == 0) {
            echo $messages;
        }
        ?>
    </div>
    <div class="form-group <?php if (isset($errors['name'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="name" class="col-sm-2 control-label"><?php echo __('courses.name') ?> <span
                class="text-danger">*</span></label>
        <div class="col-sm-10">
            <?php echo Form::text('name', Input::previous('name', $room->name), array(
                'placeholder' => __('rooms.name'),
                'autocomplete' => 'off',
                'autofocus' => 'true',
                'class' => 'form-control'
            )); ?>
            <?php if (isset($errors['name'])) { ?>
                <p class="help-block"><?php echo $errors['name'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    
    
    <div class="form-group <?php if (isset($errors['description'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="description" class="col-sm-2 control-label"><?php echo __('rooms.description') ?></label>
        <div class="col-sm-10">
            <?php echo Form::textarea('description', Input::previous('description', $room->description), array('id' => 'description', 'class' => 'form-control')); ?>
            <em><?php echo __('courses.description_explain'); ?></em>
            <?php if (isset($errors['description'])) { ?>
                <p class="help-block"><?php echo $errors['description'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="form-group text-right">
        <aside class="buttons">
            <?php echo Form::button(__('global.continue'), array(
                'type' => 'submit',
                'class' => 'btn btn-primary btn-continue',
                'data-loading' => __('global.saving')
            )); ?>
            <?php echo Html::link('admin/posts', __('global.cancel'), array(
                'class' => 'btn btn-danger btn-cancel'
            )); ?>
        </aside>
    </div>
</form>
<script src="<?php echo asset('anchor/views/assets/js/bootstrap-datetimepicker.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker_startdate').datetimepicker({
            language: 'fr',
            startDate: new Date(),
            startView: 2,
            minView: 2,
            format: 'yyyy-mm-dd',
            pickTime: false,
        });
        $('#datetimepicker_enddate').datetimepicker({
            language: 'fr',
            pickTime: false,
            startView: 2,
            minView: 2,
            format: 'yyyy-mm-dd'
        });

        $("#datetimepicker_startdate").on("changeDate", function (e) {
            $('#datetimepicker_enddate').datetimepicker('setStartDate', e.date);
        });
        $("#datetimepicker_enddate").on("changeDate", function (e) {
            $('#datetimepicker_startdate').datetimepicker('setEndDate', e.date);
        });
    });
</script>
<?php echo $footer; ?>
