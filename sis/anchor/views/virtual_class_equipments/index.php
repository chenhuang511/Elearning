<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Quản lí thiết bị'); ?></h1>

    <?php if(Auth::admin()) : ?>
    <nav>
        <?php echo Html::link('admin/virtual_class_equipments/add', __('Thêm mới'), array('class' => 'btn')); ?>
    </nav>
    <?php endif; ?>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

    <ul class="list">
        <?php foreach($virtual_class_equipments->results as $virtual_class_equipments): ?>
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
                    <tr>
                        <td class="col-sm-1"><?php echo $virtual_class_equipments->id ?></td>
                        <td class="col-sm-2">
                            <?php echo $virtual_class_equipments->name ?>
                        </td>
                        <td class="col-sm-2">
                            <?php 
                                if ($virtual_class_equipments->image_url == null) {
                            ?>
                                <img src= "<?php echo asset_url('img/noimage.jpg'); ?>" style="width: 180px; height: 150px;">
                            <?php } 
                            else{ ?>
                                <img src= "<?php echo $virtual_class_equipments->image_url; ?>" style="width: 180px; height: 150px;">    
                            <?php } ?>
                        </td>
                        <!-- <td>
                            <?php
                            if (isset($virtual_class_equipments->created) && $virtual_class_equipments->created !== NULL)
                                echo date('d-m-Y', strtotime($virtual_class_equipments->created));
                            else
                                echo 'chưa khởi tạo';
                            ?>
                        </td> -->
                        <td class="col-sm-3">
                            <?php echo $virtual_class_equipments->description ?>
                        </td>
                        <td class="col-sm-1">
                            <?php echo $virtual_class_equipments->quantity ?>
                        </td>
                        <td class="col-sm-1">
                            <?php
                                if ($virtual_class_equipments->status == 1) {
                                    echo 'Chưa được sử dụng';
                                }
                                else
                                    echo 'Đang được sử dụng';
                            ?>
                        </td>
                        <td class="col-sm-2">
                            <a href="<?php echo Uri::to('admin/virtual_class_equipments/edit/' . $virtual_class_equipments->id); ?>" class="btn btn-primary">Sửa</a>
                            <a href="<?php echo Uri::to('admin/virtual_class_equipments/delete/' . $virtual_class_equipments->id); ?>" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </ul>
</section>

<?php echo $footer; ?>