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
                    <th class="col-sm-2">Tên thiết bị</th>
                    <th class="col-sm-2">Ảnh</th><!-- 
                    <th>Ngày thêm</th> -->
                    <th class="col-sm-3">Thông tin</th>
                    <th class="col-sm-1">Số lượng</th>
                    <th class="col-sm-1">Trạng Thái</th>
                    <th class="col-sm-2">Quản lý</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rooms as $virtual_class_equipment): ?>
                    <tr>
                        <td class="col-sm-1"><?php echo $virtual_class_equipment->id ?></td>
                        <td class="col-sm-2">
                            <?php echo $virtual_class_equipment->name ?>
                        </td>
                        <td class="col-sm-2">
                            <?php 
                                if ($virtual_class_equipment->image_url == null) {
                            ?>
                                <img src= "<?php echo asset_url('img/noimage.jpg'); ?>" style="width: 180px; height: 150px;">
                            <?php } 
                            else{ ?>
                                <img src= "<?php echo $virtual_class_equipment->image_url; ?>" style="width: 180px; height: 150px;">    
                            <?php } ?>
                        </td>
                        <!-- <td>
                            <?php
                            if (isset($rooms->created) && $rooms->created !== NULL)
                                echo date('d-m-Y', strtotime($rooms->created));
                            else
                                echo 'chưa khởi tạo';
                            ?>
                        </td> -->
                        <td class="col-sm-3">
                            <?php echo $virtual_class_equipment->description ?>
                        </td>
                        <td class="col-sm-1">
                            <?php echo $virtual_class_equipment->quantity ?>
                        </td>
                        <td class="col-sm-1">
                            <?php
                                if ($virtual_class_equipment->status == 1) {
                                    echo 'Chưa được sử dụng';
                                }
                                else
                                    echo 'Đang được sử dụng';
                            ?>
                        </td>
                        <td class="col-sm-2">
                            <a href="<?php echo Uri::to('admin/rooms/edit/' . $virtual_class_equipment->id); ?>" class="btn btn-primary">Sửa</a>
                            <a href="<?php echo Uri::to('admin/rooms/delete/' . $virtual_class_equipment->id); ?>" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </ul>

    <aside class="paging"><?php // echo $school->links(); ?></aside>

</section>

<?php echo $footer; ?>
