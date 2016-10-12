<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/students'); ?>">Quản lý học viên</a></li>
    <li class="active">Danh sách khóa học</li>
</ol>
<section class="wrap">
    <hgroup class="wrap">
        <h1 style="margin: 0"><?php echo __('Danh sách khóa học'); ?></h1>
    </hgroup>
    <?php echo $messages; ?>
    <?php if ($courses->count): ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Mã</th>
                <th>Tên khoá học</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Đăng kí học</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($courses->results as $cour): ?>
                <tr>
                    <td><?php echo $cour->id ?></td>
                    <td><?php echo $cour->fullname; ?></td>
                    <td><?php
                        if ($cour->startdate !== NULL)
                            echo $cour->startdate;
                        else
                            echo 'chưa khởi tạo';
                        ?></td>
                    <td><?php
                        if ($cour->enddate !== NULL)
                            echo $cour->enddate;
                        else
                            echo 'chưa khởi tạo';
                        ?>
                    </td>
                    <td>
                    <?php $isenrol = check_user_enrolled($userid, $cour->id, $role) ?>
                    <?php if($isenrol) : ?>
                        <button data-courseid="<?= $cour->id ?>" class="btn btn-primary role-action enrol-user" data-type="unenroll">Bỏ ghi danh</button>
                    <?php else : ?>
                        <button data-courseid="<?= $cour->id ?>" class="btn btn-primary role-action enrol-user" data-type="enrol">Ghi danh</button>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <aside class="paging"><?php echo $courses->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('pages.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>
<input type="hidden" name="token" id="token" value="<?php echo Csrf::token(); ?>">
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<script src="<?php echo asset('anchor/views/assets/js/enrol-module.js'); ?>"></script>
<script>
    (function ($) {
        $(document).ready(function () {
            enrolModule.init_course(<?= $userid ?>, <?= $role ?>);
        });
    })(jQuery);
</script>

<?php echo $footer; ?>
