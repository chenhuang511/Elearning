<?php echo $header; ?>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($pages->count): ?>
        <p class="text-right">
            <a href="#" class="btn btn-success">Tạo khóa học</a>
        </p>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Thời gian</th>
                <th>Tên chuyên đề</th>
                <th>Giảng viên thực hiện</th>
                <th>Ghi chú</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                <?php foreach ($display_pages as $page) : ?>
                    <tr>
                        <td><?php echo $page->topicday ?></td>
                        <td>
                                <span class="bhxh-course">
                                    <?php if ($page->topictime !== NULL):
                                        echo '<strong>' . $page->topictime . '</strong>' . ' ' . $page->topicname;
                                    else:
                                        echo $page->topicname;
                                    endif;
                                    ?>
                                </span>
                        </td>
                        <td><?php
                            echo $page->teacher_name;
                            ?></td>
                        <td>
                            <?php
                            echo $page->note;
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo Uri::to('admin/curriculum/edit/topic/' . $page->id); ?>"
                            >Sửa <i class="fa fa-pencil" aria-hidden="true"></i></a> |
                            <a href="<?php echo Uri::to('admin/curriculum/topic/delete/' . $page->id); ?>"
                               onclick="return confirm('Bạn chắc chắn muốn xóa thông tin này');">Xóa <i class="fa fa-times"
                                                                                                 aria-hidden="true"></i></a>
                        </td>
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
