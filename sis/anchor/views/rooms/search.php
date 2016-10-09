<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php  echo __('Kết quả tìm kiếm'); ?></h1>
    <nav style="margin-top: 20px;">
        <form method="get" action="<?php echo Uri::to('admin/rooms/search'); ?>" novalidate>

            <?php echo Form::text('text-search', Input::previous('text-search'), array('id' => 'text-search')); ?>
            <?php echo Form::button('Tìm kiếm', array(
                'class' => 'btn search blue',
                'type' => 'submit'
            )); ?>

        </form>
    </nav>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

    <ul class="list">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-sm-1">ID</th>
                    <th class="col-sm-2">Tên phòng học</th>
                    <th class="col-sm-3">Thông tin</th>
                    <th class="col-sm-2">Trạng Thái</th>
                    <th class="col-sm-2">Quản lý</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rooms as $room): ?>
                    <tr>
                        <td class="col-sm-1"><?php echo $room->id ?></td>
                        <td class="col-sm-2">
                            <?php echo $room->name ?>
                        </td>
                        <td class="col-sm-3">
                            <?php echo $room->description ?>
                        </td>
                        <td class="col-sm-2">
                            <?php
                                if ($room->status == 1) {
                                    echo 'Chưa được sử dụng';
                                }
                                else
                                    echo 'Đang được sử dụng';
                            ?>
                        </td>
                        <td class="col-sm-2">
                            <a href="<?php echo Uri::to('admin/rooms/view/' . $room->id); ?>" class="btn btn-primary">Xem</a>
                            <a href="<?php echo Uri::to('admin/rooms/edit/' . $room->id); ?>" class="btn btn-primary">Sửa</a>
                            <a href="<?php echo Uri::to('admin/rooms/delete/' . $room->id); ?>" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </ul>

    <aside class="paging"><?php // echo $school->links(); ?></aside>

</section>

<?php echo $footer; ?>
