<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Quản lý phòng học</li>
</ol>

<section class="wrap">
    <p class="text-right">
        <a href="<?php echo Uri::to('admin/equipment/add/room'); ?>" class="btn btn-primary">Tạo phòng học mới</a>
    </p>
    <?php echo $messages; ?>
    <?php if ($rooms->count): ?>
    <form class="form-inline" method="get" action="<?php echo Uri::to('admin/rooms/search'); ?>" novalidate>
        <input class="form-control" type="text" name="text-search" id = "text-search" placeholder="Tên phòng học">
        <?php echo Form::button('Tìm kiếm', array(
            'class' => 'btn search blue',
            'type' => 'submit'
        )); ?>
    </form>
    <ul class="list">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-sm-1">ID</th>
                    <th class="col-sm-3">Tên phòng</th>
                    <th class="col-sm-3">Thông tin</th>
                    <th class="col-sm-2">Trạng Thái</th>
                    <th class="col-sm-3">Quản lý</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rooms->results as $room): ?>
                    <tr>
                        <td class="col-sm-1"><?php echo $room->id ?></td>
                        <td class="col-sm-3">
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
                        <td class="col-sm-3">
                            <a href="<?php echo Uri::to('admin/rooms/view/' . $room->id); ?>" class="btn btn-info">Xem</a>
                            <a href="<?php echo Uri::to('admin/rooms/edit/' . $room->id); ?>" class="btn btn-primary">Sửa</a>
                            <a href="<?php echo Uri::to('admin/rooms/delete/' . $room->id); ?>"
                               onclick="return confirm('Bạn chắc chắn muốn xóa thông tin này');" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <aside class="paging"><?php echo $rooms->links(); ?></aside>
    </ul>
    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('rooms.room_notfound'); ?><br>
        </aside>
    <?php endif; ?>
</section>

<?php echo $footer; ?>