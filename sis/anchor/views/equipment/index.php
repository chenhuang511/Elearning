<?php echo $header; ?>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($pages->count): ?>
        <p class="text-right">
            <a href="<?php echo Uri::to('admin/equipment/add/virtual_class_equipment/' . $roomid) ;?>" class="btn btn-success" id="add-remote-room">
                Tạo Thiết bị
            </a>
        </p>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Tên thiết bị</th>
                <th>Mô tả</th>
                <th>Số lượng</th>
                <th>Trạng thái</th>
                <th>Quản lí</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                <?php foreach ($display_pages as $page) : ?>
<!--                    --><?php //var_dump( $page); ?>
                    <tr>
                        <td>
                            <span class="bhxh-course">
                                <?php if ($page->virtual_class_equipmenttime !== NULL):
                                    echo '<strong>' . $page->virtual_class_equipmenttime . '</strong>' . ' ' . $page->virtual_class_equipmentname;
                                else:
                                    echo $page->virtual_class_equipmentname;
                                endif;
                                ?>
                            </span>
                        </td>
                        <td><?php
                            echo $page->description; ?>
                        </td>
                        <td>
                            <?php echo $page->quantity; ?>
                        </td>
                        <td>
                            <?php if ($page->status == 1):
                                echo 'Tốt';
                            else:
                                echo 'Hỏng';
                            endif;
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo Uri::to('admin/equipment/edit/virtual_class_equipment/' . $page->id); ?>"
                            >Sửa <i class="fa fa-pencil" aria-hidden="true"></i></a> |
                            <a href="<?php echo Uri::to('admin/equipment/virtual_class_equipment/delete/' . $page->id); ?>"
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
