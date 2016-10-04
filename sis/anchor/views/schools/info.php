<?php echo $header; ?>

<hgroup class="wrap">
    <h1 style="margin: 0"><?php echo __('Thông tin trường học'); ?></h1>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

    <fieldset class="half split">
        <p>
            <label for="label-id"><?php echo __('ID Trường'); ?></label>
            <label id="label-id"><?php echo $school->id ?></label>
        </p>
    </fieldset>

    <fieldset class="half split">
        <p>
            <label for="label-name"><?php echo __('Tên trường'); ?></label>
            <label id="label-name"><?php echo $school->name ?></label>
        </p>
    </fieldset>

    <?php if ($schoolstudent != null) { ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID Sinh viên</th>
                <th>Tên sinh viên</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($schoolstudent as $stu) : ?>
                <tr>
                    <td><?php echo $stu->id ?></td>
                    <td><?php echo $stu->fullname ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php } else echo 'Hiện tại chưa có sinh viên' ?>


    <aside class="buttons" style="display: none">
        <?php echo Form::button(__('global.update'), array(
            'class' => 'btn',
            'type' => 'submit'
        )); ?>

        <?php echo Html::link('admin/schools', __('global.cancel'), array('class' => 'btn cancel blue')); ?>

        <?php echo Html::link('admin/schools/delete/' . $school->id, __('global.delete'), array('class' => 'btn delete red')); ?>
    </aside>

</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
