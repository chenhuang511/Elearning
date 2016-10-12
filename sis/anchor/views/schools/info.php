<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/schools'); ?>">Quản lý trường học</a></li>
    <li class="active">Thông tin trường học</li>
</ol>

<hgroup class="wrap">
    <h1 style="margin: 0"><?php echo __('Thông tin trường học'); ?></h1>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th style="width: 28%;">Mã trường</th>
            <th>Tên trường</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $school->id ?></td>
                <td><?php echo $school->name ?></td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin: 50px 0 20px; color: #99a3b1">Danh sách sinh viên</h3>

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
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<?php echo $footer; ?>
