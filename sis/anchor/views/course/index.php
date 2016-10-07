<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Quản lý khóa học</li>
</ol>
<section class="wrap">
    <p class="text-right">
        <a href="<?php echo Uri::to('admin/curriculum/add/course'); ?>" class="btn btn-primary">Tạo khóa học mới</a>
    </p>
    <?php echo $messages; ?>
    <?php if ($pages->count): ?>
        <div class="table-responsive">
            <table class="table table-hover adm-table">
                <thead>
                <tr>
                    <th class="adm-code">Mã</th>
                    <th class="adm-name">Tên khoá học</th>
                    <th class="adm-date">Ngày bắt đầu</th>
                    <th class="adm-date">Ngày kết thúc</th>
                    <th class="adm-task">Quản lý</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                    <?php foreach ($display_pages as $page) : ?>
                        <tr>
                            <td><?php echo $page->id ?></td>
                            <td>
                                <a href="<?php echo Uri::to('admin/curriculum/update/course/' . $page->id); ?>">
                                <span class="bhxh-course">
                                    <?php echo $page->fullname; ?>
                                </span>
                                </a>
                            </td>
                            <td><?php
                                if ($page->startdate !== NULL)
                                    echo $page->startdate;
                                else
                                    echo 'chưa khởi tạo';
                                ?></td>
                            <td><?php
                                if ($page->enddate !== NULL)
                                    echo $page->enddate;
                                else
                                    echo 'chưa khởi tạo';
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo Uri::to('admin/curriculum/' . $page->id); ?>"><i
                                        class="fa fa-calendar" aria-hidden="true"></i>Lịch giảng</a>
                                | <a href="<?php echo Uri::to('admin/grade/course/' . $page->id); ?>"><i
                                        class="fa fa-pencil-square-o" aria-hidden="true"></i>Điểm</a>
                                | <a href="<?php echo Uri::to('admin/advance/course/' . $page->id); ?>"><i
                                        class="fa fa-usd" aria-hidden="true"></i>Tạm ứng tiền</a>
                                <?php if ($page->status == PUBLISHED) : ?>
                                    | <a href="<?php echo Uri::to('admin/courses/enrol/' . $page->id ); ?>"><i class="fa fa-users" aria-hidden="true"></i>Ghi danh</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <aside class="paging"><?php echo $pages->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('pages.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>
<?php echo $footer; ?>
