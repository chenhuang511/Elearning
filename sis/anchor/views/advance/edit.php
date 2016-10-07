<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/courses'); ?>">Quản lý khóa học</a></li>
    <li class="active"><a href="<?php echo Uri::to('admin/advance/course/' . $courseId); ?>">Tạm ứng tiền</a></li>
    <li class="active">Thay đổi</li>
</ol>

<form method="post" class="edtitopic"
      action="<?php echo Uri::to('admin/advance/course/edit/'.$courseId . '/' .$article->id); ?>"
      enctype="multipart/form-data" novalidate>
    <input name="token" type="hidden" value="<?php echo $token; ?>">
    <div class="form-group notification ">
        <?php
        if (count($errors) == 0) {
            echo $messages;
        }
        ?>
    </div>
    <div class="topic-box clearfix">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group clearfix <?php if (isset($errors['applicant_id'])) {
                            echo 'has-error';
                        } else {
                            echo '';
                        } ?>">
                        <label for="applicant_id"
                               class="control-label"><?php echo __('advance.applicant') ?><span
                                class="text-danger">*</span></label>
                        <?php echo Form::select('applicant_id', $user, Input::previous('applicant', $article->applicant_id), array(
                            'placeholder' => __('curriculum.time'),
                            'autocomplete' => 'off',
                            'autofocus' => 'true',
                            'class' => 'form-control',
                            'id' => 'applicant_id',
                        )); ?>
                        <?php if (isset($errors['applicant_id'])) { ?>
                            <p class="help-block"><?php echo $errors['applicant_id'][0] ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="note"
                               class="control-label"><?php echo __('advance.user_response') ?></span></label>
                        <?php if ($article->status !== 'draft') {
                            echo Form::text('real_name', Input::previous('real_name', $article->real_name), array('id' => 'note', 'class' => 'form-control', 'readonly' => 'true'));
                        } else {
                            echo Form::text('real_name', __('advance.not_response'), array('id' => 'note', 'class' => 'form-control', 'readonly' => 'true'));
                        } ?>

                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group <?php if (isset($errors['money'])) {
                        echo 'has-error';
                    } else {
                        echo '';
                    } ?>">
                        <label for="money"
                               class="control-label"><?php echo __('advance.money') ?> <span
                                class="text-danger">*</span></label>
                        <?php echo Form::text('money', Input::previous('money',number_format($article->money)), array('id' => 'money', 'class' => 'form-control', 'rows' => 3)); ?>
                        <input type="hidden" id="hidden_money" value="<?php echo $article->money?>" name="money">
                        <?php if (isset($errors['money'])) { ?>
                            <p class="help-block"><?php echo $errors['money'][0] ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="note"
                               class="control-label"><?php echo __('advance.status') ?><span
                                class="text-danger">*</span></label>
                        <?php echo Form::select('status', ['draft' => 'Đang yêu cầu', 'published' => 'Chấp nhận', 'rebuff' => 'Từ chối yêu cầu'], Input::previous('status', $article->status), array('id' => 'note', 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php if (isset($errors['reason'])) {
                echo 'has-error';
            } else {
                echo '';
            } ?>">
                <label for="reason"
                       class="control-label"><?php echo __('advance.reason') ?> <span
                        class="text-danger">*</span></label>
                <?php echo Form::textarea('reason', Input::previous('reason', $article->reason), array('id' => 'reason', 'class' => 'form-control', 'rows' => 5)); ?>
                <?php if (isset($errors['reason'])) { ?>
                    <p class="help-block"><?php echo $errors['reason'][0]; ?></p>
                <?php } ?>
            </div>

        </div>
    </div>
    <div class="form-group text-right">
        <p style="font-style: italic;">(Những thông tin có <span
                class="text-danger">*</span> là bắt buộc điền thông tin)</p>
    </div>
    </div>
    <div class="form-group text-right">
        <aside class="buttons">
            <?php echo Form::button(__('global.update'), array(
                'type' => 'submit',
                'class' => 'btn btn-primary btn-save',
                'data-loading' => __('global.updating')
            )); ?>
            <?php echo Html::link('admin/advance/course/' . $courseId, __('global.cancel'), array(
                'class' => 'btn btn-danger btn-cancel'
            )); ?>

        </aside>
    </div>
</form>
<script src="<?php echo asset_url('js/jquery.tablesorter.min.js'); ?>"></script>
<script src="<?php echo asset_url('js/accounting.min.js'); ?>"></script>
<script src="<?php echo asset_url('js/currency-module.js'); ?>"></script>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('#mytable').tablesorter();

            var inputs = ['#money'];
            var hiddens = ['#hidden_money'];

            currencyModule.init(accounting, inputs, hiddens);

        });
    })(jQuery);

</script>

<?php echo $footer; ?>
