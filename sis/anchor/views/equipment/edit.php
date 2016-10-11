<?php echo $header; ?>

    <form method="post" class="edtivirtual_class_equipment"
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
        <div class="virtual_class_equipment-box clearfix">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group <?php if (isset($errors['virtual_class_equipmentname'])) {
                        echo 'has-error';
                    } else {
                        echo '';
                    } ?>">
                        <label for="virtual_class_equipmentname"
                               class="control-label"><?php echo __('equipment.virtual_class_equipment') ?> <span
                                class="text-danger">*</span></label>
                        <?php echo Form::textarea('virtual_class_equipmentname', Input::previous('virtual_class_equipmentname', $equipment->virtual_class_equipmentname), array('id' => 'virtual_class_equipmentname', 'class' => 'form-control', 'rows' => 3)); ?>
                        <?php if (isset($errors['virtual_class_equipmentname'])) { ?>
                            <p class="help-block"><?php echo $errors['virtual_class_equipmentname'][0] ?></p>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="description"
                               class="control-label"><?php echo __('equipment.description') ?></label>
                        <?php echo Form::textarea('description', Input::previous('description', $equipment->description), array('id' => 'description', 'class' => 'form-control', 'rows' => 3)); ?>
                    </div>

                    <div class="form-group">
                        <label for="description"
                               class="control-label"><?php echo __('equipment.quantity') ?></label>
                        <?php echo Form::number('quantity', Input::previous('quantity', $equipment->description), array('id' => 'quantity', 'class' => 'form-control', 'rows' => 3)); ?>
                    </div>

                    <div class="form-group clearfix <?php if (isset($errors['status'])) {
                        echo 'has-error';
                    } else {
                        echo '';
                    } ?>">
                        <label for="lecturer"
                               class="control-label"><?php echo __('equipment.status') ?> <span
                                class="text-danger">*</span></label>
                        <?php echo Form::select('status', $status, Input::previous('status', $equipment->status), array('id' => 'status', 'class' => 'form-control')); ?>
                        <?php if (isset($errors['status'])) { ?>
                            <p class="help-block"><?php echo $errors['status'][0] ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="form-group text-right">
                <p style="font-style: italic;">(Những thông tin có <span
                        class="text-danger">*</span> là bắt buộc điền thông tin)</p>
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
        </div>

    </form>
    <input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
    <script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<?php echo $footer; ?>