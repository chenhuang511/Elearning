<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/courses'); ?>">Quản lý khóa học</a></li>
    <li class="active"><a href="<?php echo Uri::to('admin/advance/course/'.$course_id); ?>">Tạm ứng tiền</a></li>
    <li class="active">Thêm mới</li>
</ol>

<form method="post" class="form-horizontal" action="<?php echo Uri::to('admin/advance/course/add/'.$course_id); ?>"
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
        <div class="col-sm-4">
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
            <?php echo Form::text('money_', Input::previous('money'), array(
                'placeholder' => 'Số tiền muốn xin tạm ứng',
                'autocomplete' => 'off',
                'autofocus' => 'true',
                'class' => 'form-control' ,
                'id' =>  'money_'
            )); ?>
            <input type="hidden" id="hidden_money" value="" name="money">
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
            <?php echo Form::button(__('global.save'), array(
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
<script src="<?php echo asset_url('js/accounting.min.js'); ?>"></script>
<script src="<?php echo asset_url('js/currency-module.js'); ?>"></script>
<script type="text/javascript">
    (function($){
        $(document).ready(function () {

            var inputs = ['#money_'];
            var hiddens = ['#hidden_money'];

            currencyModule.init(accounting, inputs, hiddens);

        });
    })(jQuery);

</script>
<?php echo $footer; ?>
