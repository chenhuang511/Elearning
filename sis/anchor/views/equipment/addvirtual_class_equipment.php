<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/rooms'); ?>">Quản lý phòng học</a></li>
    <li class="active">Tạo phòng học</li>
</ol>
<form method="post" class="addvirtual_class_equipment"
      action="<?php echo Uri::to('admin/equipment/add/virtual_class_equipment/' . $roomid); ?>"
      enctype="multipart/form-data" novalidate>
    <input name="token" type="hidden" value="<?php echo $token; ?>">
    <div class="form-group notification">
        <?php
        if (count($errors) == 0) {
            echo $messages;
        }
        ?>
    </div>
    <div class="panel-group" role="tablist" id="accordion" aria-multiselectable="true">
        <?php foreach ($dates as $key => $date) { ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_virtual_class_equipment_<?php echo $key ?>">
                    <h4 class="panel-title">
                        <a href="#collapse_virtual_class_equipment_<?php echo $key ?>"
                           role="button"
                           data-toggle="collapse"
                           data-parent="#accordion"
                           aria-expanded="<?php if ($key == 1) {
                               echo 'true';
                           } else {
                               echo 'false';
                           } ?>"
                           aria-controls="collapse_virtual_class_equipment_<?php echo $key ?>">
                            <?php echo $key . '. ' . ucfirst($roomname) ?> </a>

                        <?php if ((isset($errors['virtual_class_equipment_' . $key][0]) && $errors['virtual_class_equipment_' . $key][0])) { ?>
                            <span class="text-danger"> Có lỗi<i class="fa fa-exclamation"
                                                                aria-hidden="true"></i></span>
                        <?php } ?>
                    </h4>
                </div>
                <div class="collapse panel-collapse <?php if ($key == 1) {
                    echo 'in';
                } ?>" role="tabpanel" id="collapse_virtual_class_equipment_<?php echo $key ?>"
                     aria-labelledby="heading_virtual_class_equipment_<?php echo $key ?>">
                    <div class="panel-body">
                        <div id="virtual_class_equipment_html_<?php echo $key; ?>"></div>
                        <div class="virtual_class_equipment-box clearfix">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group <?php if (isset($errors['virtual_class_equipment_' . $key])) {
                                        echo 'has-error';
                                    } else {
                                        echo '';
                                    } ?>">
                                        <label for="virtual_class_equipment_<?php echo $key; ?>"
                                               class="control-label"><?php echo __('equipment.virtual_class_equipment') ?> <span
                                                class="text-danger">*</span></label>
                                        <?php echo Form::text('virtual_class_equipment_' . $key, Input::previous('virtual_class_equipment'), array('id' => 'virtual_class_equipment_' . $key, 'class' => 'form-control')); ?>
                                        <?php if (isset($errors['virtual_class_equipment_' . $key])) { ?>
                                            <p class="help-block"><?php echo $errors['virtual_class_equipment_' . $key][0] ?></p>
                                        <?php } ?>
                                    </div>

                                    <div class="form-group <?php if (isset($errors['description_virtual_class_equipment_' . $key])) {
                                        echo 'has-error';
                                    } else {
                                        echo '';
                                    } ?>">
                                        <label for="virtual_class_equipment_<?php echo $key; ?>"
                                               class="control-label"><?php echo __('equipment.description') ?> <span
                                                class="text-danger">*</span></label>
                                        <?php echo Form::textarea('description_virtual_class_equipment_' . $key, Input::previous('description'), array('id' => 'description_virtual_class_equipment_' . $key, 'class' => 'form-control', 'rows' => 3)); ?>

                                        <?php if (isset($errors['virtual_class_equipment_' . $key])) { ?>
                                            <p class="help-block"><?php echo $errors['description_virtual_class_equipment_' . $key][0] ?></p>
                                        <?php } ?>
                                    </div>

                                    <div class="form-group <?php if (isset($errors['quantity-virtual_class_equipment_' . $key])) {
                                        echo 'has-error';
                                    } else {
                                        echo '';
                                    } ?>">
                                        <label for="virtual_class_equipment_<?php echo $key; ?>"
                                               class="control-label"><?php echo __('equipment.quantity') ?> <span
                                                class="text-danger">*</span></label>
                                        <?php echo Form::number('quantity_virtual_class_equipment_' . $key, Input::previous('quantity_virtual_class_equipment'), array('id' => 'quantity_virtual_class_equipment_' . $key, 'type' => 'number', 'class' => 'form-control')); ?>
                                        <?php if (isset($errors['virtual_class_equipment_' . $key])) { ?>
                                            <p class="help-block"><?php echo $errors['quantity_virtual_class_equipment_' . $key][0] ?></p>
                                        <?php } ?>
                                    </div>

                                    <div class="form-group clearfix <?php if (isset($errors['status'])) {
                                        echo 'has-error';
                                    } else {
                                        echo '';
                                    } ?>">
                                        <label for="lecturer"
                                               class="control-label"><?php echo __('equipment.status') ?> <span
                                                class="text-danger">*</span></label>
                                        <?php echo Form::select('status', array('1' => 'Tốt', '0' => 'Hỏng', '1'), Input::previous('status'), array('id' => 'status_virtual_class_equipment_', 'class' => 'form-control')); ?>
                                        <?php if (isset($errors['status'])) { ?>
                                            <p class="help-block"><?php echo $errors['status'][0] ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <input type="hidden" id="id_virtual_class_equipment_<?php echo $key; ?>"
                                       name="id_virtual_class_equipment_<?php echo $key; ?>" value="<?php echo $key; ?>">
                                <input type="hidden" id="content_virtual_class_equipment_<?php echo $key; ?>"
                                       name="content_virtual_class_equipment_<?php echo $key; ?>" value="">
                                <p style="font-style: italic;">(Những thông tin có <span
                                        class="text-danger">*</span> là bắt buộc điền thông tin)</p>
                                <a href="#" id="add_new_virtual_class_equipment_<?php echo $key; ?>"
                                   class="btn btn-success"><?php echo __('equipment.addvirtual_class_equipment') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="form-group text-right">
        <aside class="buttons">
            <?php echo Form::button(__('global.save'), array(
                'type' => 'submit',
                'class' => 'btn btn-primary btn-save',
                'data-loading' => __('global.saving')
            )); ?>
            <?php echo Html::link('admin/equipment/' . $roomid, __('global.cancel'), array(
                'class' => 'btn btn-danger btn-cancel'
            )); ?>
        </aside>
    </div>
</form>
<script src="<?php echo asset('anchor/views/assets/js/virtualclassequipment-module.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<script type="text/javascript">
    $(function () {
        $(document).ready(function () {
            virtualclassequipmentModule.init();
        });
    });
</script>
<?php echo $footer; ?>