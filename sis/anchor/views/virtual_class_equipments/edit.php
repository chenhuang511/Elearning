<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('Thêm mới thiết bị'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>
	<?php if(Auth::admin()) : ?>

		<div class="col-md-12">
            <form action="<?php echo Uri::to('admin/virtual_class_equipments/edit/' . $virtual_class_equipments->id); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
				<input name="token" type="hidden" value="<?php echo $token; ?>">                
                <table class="table">
                    <tbody>
                    <tr>
                        <td>TÊN: </td>
                        <td>
                            <?php echo Form::text('name', Input::previous('name', $virtual_class_equipments->name), array('id' => 'label-name')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>SÔ LƯƠNG: </td>
                        <td>
                            <?php echo Form::text('quantity', Input::previous('quantity', $virtual_class_equipments->quantity), array('id' => 'label-quantity')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Mô tả: </td>
                        <td>
                            <?php echo Form::text('description', Input::previous('description', $virtual_class_equipments->description), array('id' => 'label-description')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>TRẠNG THÁI: </td>
                        <td>
                            <select name="status">
                                <option value="0" <?php if($virtual_class_equipments->status == 0) echo "selected"; ?>>Đang được sử dụng</option>
                                <option value="1" <?php if($virtual_class_equipments->status == 1) echo "selected"; ?>>Chưa được sử dụng</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>ẢNH: </td>
                        <td>
                        	<?php echo Form::file('image_url'); ?>
                            <img src="<?php echo $virtual_class_equipments->image_url; ?>" id="image" height="150px" width="180px"/>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <aside class="buttons">
					<?php echo Form::button(__('global.update'), array(
						'class' => 'btn btn-primary',
						'type' => 'submit'
					)); ?>

					<?php echo Html::link('admin/virtual_class_equipments' , __('global.cancel'), array('class' => 'btn btn-warning')); ?>
				</aside>
        </div>

		
	</form>
	<?php else : ?>
		<p>You do not have the required privileges to add virtual class equipments, you must be an Administrator. Please contact the Administrator of the site if you are supposed to have these privileges.</p>
		<br><a class="btn" href="<?php echo Uri::to('admin/virtual_class_equipments'); ?>">Go back</a>
	<?php endif; ?>
</section>

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
        $('[name="image_url"]').change(function(){
        readURL(this);
    });
    </script>

<?php echo $footer; ?>
