<?php echo $header; ?>

<hgroup class="wrap">
    <h1 style="margin: 0"><?php echo __('Thông tin sinh viên'); ?></h1>
</hgroup>


<section class="wrap">
    <?php echo $messages; ?>

    <fieldset class="one-third split">
        <p>
            <label for="label-fullname"><?php echo __('students.fullname') ?></label>
            <label id="label-fullname"><?php echo $student->fullname ?></label>
        </p>
    </fieldset>

    <fieldset class="one-third split">
        <p>
            <label for="label-email"><?php echo __('students.email'); ?></label>
            <label id="label-email"><?php echo $student->email ?></label>
        </p>
    </fieldset>

    <fieldset class="one-third split">
        <?php foreach ($studentschool as $stusch) { ?>
            <p>
                <label for="label-schoolname"><?php echo __('Tên trường'); ?></label>
                <label id="label-schoolname"><?php echo $stusch->name ?></label>
            </p>
        <?php } ?>
    </fieldset>


    <table class="table table-hover">
        <thead>
        <tr>
            <th>Tên khóa học</th>
            <th>Điểm tổng kết</th>
            <th>Chứng chỉ khóa học</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($studentcourse as $stu) : ?>
            <tr>
                <td><?php echo $stu->fullname ?></td>
                <td style="text-align: center"><?php echo $stu->grade ?></td>
                <td style="text-align: center">
                    <?php
                    $certificate = remote_get_link_certificate($stu->schoolid, $stu->studentid, $stu->id);
                    if ( $certificate != 'false' && !empty($certificate)) { ?>
                        <a target="_blank" class="btn btn-primary" href="<?php echo $certificate; ?>" >Chứng chỉ</a>
                    <?php } else { ?>
                        <a class="btn btn-primary" href="#" >Chứng chỉ</a>
                    <?php } ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
