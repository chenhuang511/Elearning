<?php echo $header; ?>

    <hgroup class="wrap">
        <h1><?php echo __('Quản lí thiết bị'); ?></h1>

        <?php if (Auth::admin()) : ?>
            <div style="float: right; margin: 20px 0 0 20px;">
                <?php echo Html::link('admin/virtual_class_equipments/add', __('Thêm mới'), array('class' => 'btn')); ?>
            </div>

        <?php endif; ?>
        <form style="float: right; margin-top: 20px;" method="get"
              action="<?php echo Uri::to('admin/virtual_class_equipments/search'); ?>" novalidate>
            <?php echo Form::text('text-search', Input::previous('text-search'), array('id' => 'text-search')); ?>
            <?php echo Form::button('Tìm kiếm', array(
                'class' => 'btn search blue',
                'type' => 'submit'
            )); ?>
        </form>
    </hgroup>

    <section class="wrap">
        <?php echo $messages; ?>
        <?php if ($pages->count): ?>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-sm-1">ID</th>
                    <th class="col-sm-2">Tên thiết bị</th>
                    <th class="col-sm-2">Ảnh</th>
                    <th class="col-sm-3">Thông tin</th>
                    <th class="col-sm-1">Số lượng</th>
                    <th class="col-sm-1">Trạng Thái</th>
                    <th class="col-sm-2">Quản lý</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                    <?php foreach ($display_pages as $page) : ?>
                        <tr>
                            <td class="col-sm-1"><?php echo $page->id ?></td>
                            <td class="col-sm-2">
                                <?php echo $page->name ?>
                            </td>
                            <td class="col-sm-2">
                                <?php
                                if ($page->image_url == null) {
                                    ?>
                                    <img src="<?php echo asset_url('img/noimage.jpg'); ?>"
                                         style="width: 180px; height: 150px;">
                                <?php } else { ?>
                                    <img src="<?php echo $page->image_url; ?>"
                                         style="width: 180px; height: 150px;">
                                <?php } ?>
                            </td>
                            <!-- <td>
                            <?php
                            if (isset($page->created) && $page->created !== NULL)
                                echo date('d-m-Y', strtotime($page->created));
                            else
                                echo 'chưa khởi tạo';
                            ?>
                        </td> -->
                            <td class="col-sm-3">
                                <?php echo $page->description; ?>
                            </td>
                            <td class="col-sm-1">
                                <?php echo $page->quantity; ?>
                            </td>
                            <td class="col-sm-1">
                                <?php
                                if ($page->status == 1) {
                                    echo 'Chưa được sử dụng';
                                } else
                                    echo 'Đang được sử dụng';
                                ?>
                            </td>
                            <td class="col-sm-2">
                                <a href="<?php echo Uri::to('admin/virtual_class_equipments/edit/' . $page->id); ?>"
                                   class="btn btn-primary">Sửa</a>
                                <a href="<?php echo Uri::to('admin/virtual_class_equipments/delete/' . $page->id); ?>"
                                   class="btn btn-danger">Xóa</a>
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