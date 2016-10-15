<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Phòng học'); ?></h1>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>
    <?php if (Auth::admin()) : ?>
        <p class="text-right">
            <a href="<?php echo Uri::to('admin/equipment/' . $rooms[0]->id); ?>" class="btn btn-success" id="">
                Xem thiết bị
            </a>
        </p>
        <div class="col-md-12">
            <form action="<?php echo Uri::to('admin/rooms/edit/' . $rooms[0]->id); ?>" method="POST"
                  enctype="multipart/form-data" autocomplete="off">
                <input name="token" type="hidden" value="<?php echo $token; ?>">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>TÊN:</td>
                        <td>
                            <?php echo Form::text('name', Input::previous('name', $rooms[0]->name), array('id' => 'label-name', 'readonly' => 'readonly')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>MÔ TẢ:</td>
                        <td>
                            <?php echo Form::text('description', Input::previous('description', $rooms[0]->description), array('id' => 'label-description', 'readonly' => 'readonly')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>TRẠNG THÁI:</td>
                        <td>
                            <div id = "status">
                                <?php
                                if ($rooms[0]->status = 1){
                                    echo "Phòng học đang được sử dụng";
                                }
                                    else "Phòng học còn trống";
                                ?>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <aside class="buttons">
                    <a href="<?php echo Uri::to('admin/rooms/edit/' . $rooms[0]->id); ?>" class="btn btn-primary">Sửa</a>
                    <a href="<?php echo Uri::to('admin/rooms/delete/' . $rooms[0]->id); ?>"
                       onclick="return confirm('Bạn chắc chắn muốn xóa thông tin này');" class="btn btn-danger">Xóa</a>
                </aside>
        </div>


        </form>
    <?php else : ?>
        <p>You do not have the required privileges to add virtual class equipments, you must be an Administrator. Please
            contact the Administrator of the site if you are supposed to have these privileges.</p>
        <br><a class="btn" href="<?php echo Uri::to('admin/rooms'); ?>">Go back</a>
    <?php endif; ?>
</section>
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $('[name="image_url"]').change(function () {
        readURL(this);
    });
</script>

<?php echo $footer; ?>

