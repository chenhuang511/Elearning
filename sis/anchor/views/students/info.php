<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/students'); ?>">Quản lý học viên</a></li>
    <li class="active">Thông tin học viên</li>
</ol>

<hgroup class="wrap">
    <h1 style="margin: 0"><?php echo __('Thông tin học viên'); ?></h1>
</hgroup>


<section class="wrap">
    <?php echo $messages; ?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Tên học viên</th>
            <th>Email</th>
            <th>Tên trường</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $student->fullname ?></td>
                <td><?php echo $student->email ?></td>
                <?php foreach ($studentschool as $stusch) { ?>
                        <td><?php echo $stusch->name ?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>


    <h3 style="color: #99a3b1; margin: 50px 0 20px;">Số chuyên đề đã hoàn thành : <?php echo $counttopicsuccessed; ?></h3>
    <?php if ($counttopicsuccessed > 0) { ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Mã khóa học</th>
                <th>Mã chuyên đề</th>
                <th>Tên chuyên đề</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($topicsuccessed as $stu) : ?>
                <tr>
                    <td><?php echo $stu->course ?></td>
                    <td><?php echo $stu->id ?></td>
                    <td><?php echo $stu->topicname ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Chưa có chuyên đề nào đã hoàn thành</p>
    <?php } ?>


    <h3 style="color: #99a3b1; margin: 50px 0 20px;">Các khóa học đã hoàn thành</h3>

    <?php if ($countcoursesuccessed > 0) { ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Tên khóa học</th>
                <th style="text-align: center">Điểm tổng kết</th>
                <th style="text-align: center">Chứng chỉ khóa học</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($coursesuccessed as $stu) : ?>

                    <tr>
                        <td style="width: 650px"><?php echo $stu->fullname ?></td>
                        <?php  $gradecomplete = remote_get_grade_complete_course($thisstudent->remoteid, $stu->id) ?>
                        <td style="text-align: center"><?php echo $gradecomplete ?></td>
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
                <?php  endforeach; ?>
            </tbody>
        </table>

    <?php } else { ?>
        <p>Chưa có khóa học nào đã hoàn thành</p>
    <?php } ?>


    <h3 style="color: #99a3b1; margin: 50px 0 20px;">Các khóa học chưa hoàn thành</h3>

    <?php if ($countcourselearning > 0) { ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Tên khóa học</th>
                <th style="text-align: center">Tiến độ hoàn thành</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($courselearning as $stu) : ?>
                <tr>
                    <td style="width: 862px"><?php echo $stu->fullname ?></td>
                    <td style="text-align: center">
                        <?php $percent = remote_get_percent_course($stu->schoolid, $stu->remoteid, $thisstudent->remoteid);
                         echo $percent . '%' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php } else { ?>
        <p>Không có khóa học nào chưa hoàn thành</p>
    <?php } ?>

</section>
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
