<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="/admin">Trang chủ</a></li>
    <li class="active">Quản lý khóa học</li>
</ol>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($pages->count): ?>
        <table class="table table-hover adm-table">
            <thead>
            <tr>
                <th>Mã</th>
                <th>Tên khoá học</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Quản lý</th>
                <th>Quản lý điểm</th>
                <th>Tạm ứng tiền</th>
                <th>Ghi danh</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                <?php foreach ($display_pages as $page) : ?>
                    <tr>
                        <td><?php echo $page->data['id'] ?></td>
                        <td>
                            <a href="<?php echo Uri::to('admin/courses/edit/' . $page->data['id']); ?>">
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
                            <a href="<?php echo Uri::to('admin/curriculum/' . $page->id); ?>"
                               class="btn btn-primary">lịch giảng</a>
                        </td>
                        <td>
                            <a href="<?php echo Uri::to('admin/grade/course/' . $page->id); ?>"
                               class="btn btn-primary">quản lý điểm</a>
                        </td>
                        <td>
                            <a href="<?php echo Uri::to('admin/advance/course/' . $page->id); ?>"
                               class="btn btn-primary">tạm ứng</a>
                        </td>
                        <?php if($page->status == PUBLISHED) : ?>
                            <td>
                                <a href="<?php echo Uri::to('admin/courses/' . $page->id . '/enrol'); ?>"
                                   class="btn btn-primary">Ghi danh</a>
                            </td>
                        <?php else : ?>
                            <td>
                                <a href="#"
                                   class="btn btn-primary hidden">Ghi danh</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <aside class="paging"><?php echo $pages->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('pages.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>
<?php echo $footer; ?>
