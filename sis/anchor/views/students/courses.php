<?php echo $header; ?>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($courses->count): ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Mã</th>
                <th>Tên khoá học</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Quản lý</th>
                <th>Quản lý điểm</th>
                <th>Tạm ứng tiền</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($courses->results as $cour): ?>
                    <tr>
                        <td><?php echo $cour->id ?></td>
                        <td>
<!--                            <a href="--><?php //echo Uri::to('admin/courses/edit/' . $page->data['id']); ?><!--">-->
                                <span class="bhxh-course">
                                    <?php echo $cour->fullname; ?>
                                </span>
<!--                            </a>-->
                        </td>
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
                            <a href="<?php echo Uri::to('admin/curriculum/' . $cour->id); ?>"
                               class="btn btn-primary">lịch giảng</a>
                        </td>
                        <td>
                            <a href="<?php echo Uri::to('admin/grade/course/' . $cour->id); ?>"
                               class="btn btn-primary">quản lý điểm</a>
                        </td>
                        <td>
                            <a href="<?php echo Uri::to('admin/advance/course/' . $cour->id); ?>"
                               class="btn btn-primary">tạm ứng</a>
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
<?php echo $footer; ?>
