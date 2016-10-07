<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/students'); ?>">Quản lý sinh viên</a></li>
    <li class="active">Thông tin sinh viên</li>
</ol>

<hgroup class="wrap">
    <h1 style="margin: 0"><?php echo __('Thông tin sinh viên'); ?></h1>
</hgroup>


<section class="wrap">
    <?php echo $messages; ?>
    <div style="display: flex">
    <fieldset class="half split">
        <p>
            <label for="label-fullname"><?php echo __('students.fullname') ?></label>
            <label id="label-fullname"><?php echo $student->fullname ?></label>
        </p>
        <p>
            <label for="label-email"><?php echo __('students.email'); ?></label>
            <label id="label-email"><?php echo $student->email ?></label>
        </p>
    </fieldset>

    <fieldset class="half split">
        <?php foreach ($studentschool as $stusch) { ?>
            <p>
                <label for="label-schoolname"><?php echo __('Tên trường'); ?></label>
                <label id="label-schoolname"><?php echo $stusch->name ?></label>
            </p>
        <?php } ?>
        <p style="height: 39px;"></p>
    </fieldset>
    </div>

    <h3>Số chuyên đề đã hoàn thành : <?php echo $counttopicsuccessed; ?></h3>
    <?php if ($counttopicsuccessed > 0) { ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th style="text-align: center">Mã khóa học</th>
                <th style="text-align: center">Mã chuyên đề</th>
                <th style="text-align: center">Tên chuyên đề</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($studenttopicsuccessed as $stu) : ?>
                <tr>
                    <td style="text-align: center"><?php echo $stu->courseid ?></td>
                    <td style="text-align: center"><?php echo $stu->id ?></td>
                    <td style="text-align: center"><?php echo $stu->topicname ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php }
    else { ?>
        <p>Chưa có chuyên đề nào đã hoàn thành</p>
    <?php } ?>


    <h3>Các khóa học đã hoàn thành</h3>
    <?php if ($studentcoursesuccessed != null) { ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Tên khóa học</th>
                <th style="text-align: center">Điểm tổng kết</th>
                <th style="text-align: center">Chứng chỉ khóa học</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($studentcoursesuccessed as $stu) : ?>
                <tr>
                    <td style="width: 650px"><?php echo $stu->fullname ?></td>
                    <td style="text-align: center"><?php echo $stu->grade ?></td>
                    <td style="text-align: center">
                        <?php
                        $certificate = remote_get_link_certificate($stu->schoolid, $stu->studentid, $stu->id);
                        if ($certificate != 'false' && !empty($certificate)) { ?>
                            <a target="_blank" class="btn btn-primary" href="<?php echo $certificate; ?>">Chứng chỉ</a>
                        <?php } else { ?>
                            <a class="btn btn-primary" href="#">Chứng chỉ</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php }
    else { ?>
        <p>Chưa có khóa học nào đã hoàn thành</p>
    <?php } ?>

    <h3>Các khóa học chưa hoàn thành <?php // echo $counttopiclearning; ?></h3>

    <?php if ($studentcourselearning != null) { ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Tên khóa học</th>
                <th style="text-align: center">Chứng chỉ khóa học</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($studentcourselearning as $stu) : ?>
                <tr>
                    <td style="width: 862px"><?php echo $stu->fullname ?></td>
                    <td style="text-align: center">
                        <?php
                        $certificate = remote_get_link_certificate($stu->schoolid, $stu->studentid, $stu->id);
                        if ($certificate != 'false' && !empty($certificate)) { ?>
                            <a target="_blank" class="btn btn-primary" href="<?php echo $certificate; ?>">Chứng chỉ</a>
                        <?php } else { ?>
                            <a class="btn btn-primary" href="#">Chứng chỉ</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php }
    else { ?>
        <p>Không có khóa học nào chưa hoàn thành</p>
    <?php } ?>

</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
