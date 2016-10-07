<?php echo $header; ?>

    <form method="post" class="edtitopic"
          action="<?php echo Uri::to('admin/equipment/edit/virtual_class_equipment/' . $equipment->id); ?>"
          enctype="multipart/form-data" novalidate>
        <input name="token" type="hidden" value="<?php echo $token; ?>">
        <div class="form-group notification">
            <?php
            if (count($errors) == 0) {
                echo $messages;
            }
            ?>
        </div>
        <div class="topic-box clearfix">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="topictime"
                               class="control-label"><?php echo __('equipment.time') ?></label>
                        <?php echo Form::text('virtual_class_equipmenttime', Input::previous('virtual_class_equipmenttime', $equipment->topictime), array(
                            'placeholder' => __('equipment.time'),
                            'autocomplete' => 'off',
                            'autofocus' => 'true',
                            'class' => 'form-control',
                            'id' => 'virtual_class_equipmenttime',
                            'size' => 20,
                            'maxlength' => 20
                        )); ?>
                    </div>
                    <div class="form-group <?php if (isset($errors['topicname'])) {
                        echo 'has-error';
                    } else {
                        echo '';
                    } ?>">
                        <label for="topicname"
                               class="control-label"><?php echo __('equipment.virtual_class_equipment') ?> <span
                                class="text-danger">*</span></label>
                        <?php echo Form::textarea('virtual_class_equipmentname', Input::previous('virtual_class_equipmentname', $equipment->virtual_class_equipmentname), array('id' => 'virtual_class_equipmentname', 'class' => 'form-control', 'rows' => 3)); ?>
                        <?php if (isset($errors['virtual_class_equipmentname'])) { ?>
                            <p class="help-block"><?php echo $errors['virtual_class_equipmentname'][0] ?></p>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group clearfix <?php if (isset($errors['lecturer'])) {
                        echo 'has-error';
                    } else {
                        echo '';
                    } ?>">
                        <label for="lecturer"
                               class="control-label"><?php echo __('equipment.teacher') ?> <span
                                class="text-danger">*</span></label>
                        <?php echo Form::select('lecturer', $teachers, Input::previous('lecturer', $equipment->lecturer), array('id' => 'lecturer', 'class' => 'form-control')); ?>
                        <?php if (isset($errors['lecturer'])) { ?>
                            <p class="help-block"><?php echo $errors['lecturer'][0] ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="note"
                               class="control-label"><?php echo __('equipment.note') ?></label>
                        <?php echo Form::textarea('note', Input::previous('note', $equipment->note), array('id' => 'note', 'class' => 'form-control', 'rows' => 3)); ?>
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
                <?php echo Html::link('admin/equipment/' . $equipment->room, __('global.cancel'), array(
                    'class' => 'btn btn-danger btn-cancel'
                )); ?>
            </aside>
        </div>
    </form>
    <script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<?php echo $footer; ?>