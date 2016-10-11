<?php echo $header; ?>
<form method="post" action="<?php echo Uri::to('admin/posts/edit/' . $article->id); ?>" enctype="multipart/form-data" novalidate class="form-horizontal " >
	<input name="token" type="hidden" value="<?php echo $token; ?>">
	<div class="form-group notification">
		<?php
		if (count($errors) == 0) {
			echo $messages;
		}
		?>
	</div>
	<div class="form-group clearfix <?php if (isset($errors['title'])) {
		echo 'has-error';
	} else {
		echo '';
	} ?>">
		<label for="fullname" class="col-sm-2 control-label"><?php echo __('posts.title') ?> <span
				class="text-danger">*</span></label>
		<div class="col-sm-10 clearfix">

			<?php echo Form::text('title', Input::previous('title', $article->title), array(
				'placeholder' => __('posts.title'),
				'autocomplete' => 'off',
				'autofocus' => 'true',
				'class' => 'form-control'
			)); ?>
			<?php if (isset($errors['title'])) { ?>
				<p class="help-block"><?php echo $errors['title'][0] ?></p>
			<?php } ?>
		</div>
	</div>
	<div class=" form-group <?php if (isset($errors['category'])) {
		echo 'has-error';
	} else {
		echo '';
	} ?>">
		<label for="label-category" class="col-sm-2 control-label"><?php echo __('posts.category') ?><span
				class="text-danger">*</span></label>
		<div class="col-sm-4">

			<?php echo Form::select('category', $categories ,Input::previous('category',$article->category), array('id' => 'label-category', 'class' => 'form-control')); ?>
			<?php if (isset($errors['category'])) { ?>
				<p class="help-block"><?php echo $errors['category'][0] ?></p>
			<?php } ?>
		</div>
	</div>
	<div class="form-group  <?php if (isset($errors['slug'])) {
		echo 'has-error';
	} else {
		echo '';
	} ?>">
		<label for="label-slug" class="col-sm-2 control-label label-slug"><?php echo __('posts.slug'); ?> <span
				class="text-danger">*</span></label>
		<div class="col-sm-4 ">
			<?php echo Form::text('slug', Input::previous('slug',$article->slug), array(
				'placeholder' => __('posts.slug'),
				'autocomplete' => 'off',
				'autofocus' => 'true',
				'class' => 'form-control',
				'id' => 'slug',
			)); ?>
			<?php if (isset($errors['slug'])) { ?>
				<p class="help-block"><?php echo $errors['slug'][0] ?></p>
			<?php } ?>
		</div>
	</div>
	<div class="form-group clearfix <?php if (isset($errors['description'])) {
		echo 'has-error';
	} else {
		echo '';
	} ?>">
		<label for="label-description" class="col-sm-2 control-label"><?php echo __('posts.description') ?></label>
		<div class="col-sm-10 ">

			<?php echo Form::textarea('description', Input::previous('description', $article->description), array('id' => 'label-description', 'class' => 'form-control', 'rows' => '3')); ?>
			<?php if (isset($errors['description'])) { ?>
				<p class="help-block"><?php echo $errors['description'][0] ?></p>
			<?php } ?>
		</div>
	</div>
	<div class="form-group clearfix <?php if (isset($errors['markdown'])) {
		echo 'has-error';
	} else {
		echo '';
	} ?>">
		<label for="markdown" class="col-sm-2 control-label">Nội dung<span
				class="text-danger">*</span></label>
		<div class="col-sm-10 clearfix">

			<?php echo Form::textarea('markdown', Input::previous('markdown', $article->markdown), array(
				'placeholder' => __('posts.content_explain'),
				'autocomplete' => 'off',
				'autofocus' => 'true',
				'class' => 'form-control',
				'id' => 'markdown',
			)); ?>
			<?php if (isset($errors['markdown'])) { ?>
				<p class="help-block"><?php echo $errors['markdown'][0] ?></p>
			<?php } ?>
		</div>
	</div>
    <div class="form-group <?php if (isset($errors['created'])) {
        echo 'has-error';
    } else {
        echo '';
    } ?>">
        <label for="label-time"
               class="col-sm-2 control-label"><?php echo __('posts.time') ?> <span
                class="text-danger">*</span></label>
        <div class="col-sm-4">
            <?php echo Form::text('created', Input::previous('slug',$article->created), array(
                'placeholder' => __('posts.time'),
                'autocomplete' => 'off',
                'autofocus' => 'true',
                'class' => 'form-control',
                'id' => 'created',
                'readonly' => 'true'
            )); ?>

        <?php if (isset($errors['created'])) { ?>
            <p class="help-block"><?php echo $errors['created'][0] ?></p>
        <?php } ?>
        </div>
    </div>

	<div class=" form-group <?php if (isset($errors['status'])) {
		echo 'has-error';
	} else {
		echo '';
	} ?>">
		<label for="label-status" class="col-sm-2 control-label"><?php echo __('posts.status') ?><span
				class="text-danger">*</span></label>
		<div class="col-sm-4 ">
			<?php echo Form::select('status', $statuses, Input::previous('status', $article->status), array('id' => 'label-status', 'class' => 'form-control')); ?>
			<?php if (isset($errors['status'])) { ?>
				<p class="help-block"><?php echo $errors['status'][0] ?></p>
			<?php } ?>
		</div>
	</div>

	<?php foreach($fields as $field): ?>


			<label for="extend_<?php echo $field->key; ?>" class="col-sm-2 control-label"><?php echo $field->label; ?></label>
			<div class="col-sm-4">
                <label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
				<?php echo Extend::html($field); ?>

			</div>
		</div>
	<?php endforeach; ?>

	<div class="form-group text-right" style="padding-right: 15px;">
		<aside class="buttons">
			<?php echo Form::button(__('global.continue'), array(
				'type' => 'submit',
				'class' => 'btn btn-primary btn-continue',
				'data-loading' => __('global.saving'),
				'id' => 'submit'
			)); ?>
            <?php echo Html::link('admin/posts' , __('global.cancel'), array(
                'class' => 'btn cancel blue'
            )); ?>

            <?php echo Html::link('admin/posts/delete/' . $article->id, __('global.delete'), array(
                'class' => 'btn delete red'
            )); ?>
		</aside>
	</div>
</form>
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">

<script src="<?php echo asset('anchor/views/assets/js/slug.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/dragdrop.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/change-saver.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/bootstrap-datetimepicker.js'); ?>"></script>
<script>
    var editor = CKEDITOR.replace( 'markdown');
    $('#submit').click(function() {
        var value = editor.getData() ;
        console.log(value) ;
        $('#markdown').val(value) ;
        // send your ajax request with value// profit!
    });
    $('#datetimepicker').datetimepicker({
        language: 'fr',
        pickTime: false,
        startView: 2,
        minView: 2,
        format: 'yyyy-mm-dd hh:ii:ss',
        pickTime: true,
    });
</script>

<?php echo $footer; ?>
