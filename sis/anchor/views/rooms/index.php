<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Quản lí phòng học'); ?></h1>

    <?php if(Auth::admin()) : ?>

    <?php endif; ?>
    <form style="float: right; margin-top: 20px;" method="get" action="<?php echo Uri::to('admin/rooms/search'); ?>" novalidate>
        <?php echo Form::text('text-search', Input::previous('text-search'), array('id' => 'text-search')); ?>
        <?php echo Form::button('Tìm kiếm', array(
            'class' => 'btn search blue',
            'type' => 'submit'
        )); ?>
    </form>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

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
                <?php foreach($rooms->results as $rooms): ?>
                    <tr>
                        <td class="col-sm-1"><?php echo $rooms->id ?></td>
                        <td class="col-sm-3">
                            <?php echo $rooms->name ?>
                        </td>
                        <td class="col-sm-3">
                            <?php echo $rooms->description ?>
                        </td>
                        <td class="col-sm-2">
                            <?php
                                if ($rooms->status == 1) {
                                    echo 'Chưa được sử dụng';
                                }
                                else
                                    echo 'Đang được sử dụng';
                            ?>
                        </td>
                        <td class="col-sm-3">
                            <a href="<?php echo Uri::to('admin/rooms/view/' . $rooms->id); ?>" class="btn btn-info">Xem</a>
                            <a href="<?php echo Uri::to('admin/rooms/edit/' . $rooms->id); ?>" class="btn btn-primary">Sửa</a>
                            <a href="<?php echo Uri::to('admin/rooms/delete/' . $rooms->id); ?>" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </ul>
</section>

<?php echo $footer; ?>