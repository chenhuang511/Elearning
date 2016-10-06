<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>
</hgroup>
<form method="post" class="form-horizontal" action="<?php echo Uri::to('admin/advance/course/'.$course_id.'/add'); ?>"
      enctype="multipart/form-data" novalidate>
    <input name="token" type="hidden" value="<?php echo $token; ?>">
    <div class="form-group notification">
        <?php
        if (count($errors) == 0) {
            echo $messages;
        }
        ?>
    </div>

    <div class="form-group <?php if (isset($errors['applicant_id'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="fullname" class="col-sm-2 control-label"><?php echo __('advance.applicant') ?> <span
                class="text-danger">*</span></label>
        <div class="col-sm-10">
            <?php echo Form::select('applicant_id', $user, Input::previous('applicant_id'), array(
                'autocomplete' => 'off',
                'autofocus' => 'true',
                'class' => 'form-control'
            )); ?>
            <?php if (isset($errors['applicant_id'])) { ?>
                <p class="help-block"><?php echo $errors['applicant_id'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="form-group <?php if (isset($errors['money'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="money" class="col-sm-2 control-label"><?php echo __('advance.money') ?> <span
                class="text-danger">*</span></label>
        <div class="col-sm-4">
            <?php echo Form::text('money', Input::previous('money'), array(
                'placeholder' => 'Số tiền muốn xin tạm ứng',
                'autocomplete' => 'off',
                'autofocus' => 'true',
                'class' => 'form-control'
            )); ?>
            <?php if (isset($errors['money'])) { ?>
                <p class="help-block"><?php echo $errors['money'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="form-group <?php if (isset($errors['reason'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="reason" class="col-sm-2 control-label"><?php echo __('advance.reason') ?><span
                class="text-danger"> *</label>
        <div class="col-sm-10">
            <?php echo Form::textarea('reason', Input::previous('reason'), array('id' => 'reason', 'class' => 'form-control')); ?>
            <em><?php echo __('courses.summary_explain'); ?></em>
            <?php if (isset($errors['reason'])) { ?>
                <p class="help-block"><?php echo $errors['reason'][0] ?></p>
            <?php } ?>
        </div>
    </div>
    <div class="form-group text-right" style="padding-right: 15px;">
        <aside class="buttons">
            <?php echo Form::button(__('global.continue'), array(
                'type' => 'submit',
                'class' => 'btn btn-primary btn-continue',
                'data-loading' => __('global.saving')
            )); ?>
            <?php echo Html::link('admin/advance/course/'.$course_id, __('global.cancel'), array(
                'class' => 'btn btn-danger btn-cancel'
            )); ?>
        </aside>
    </div>
</form>

<?php echo $footer; ?>
