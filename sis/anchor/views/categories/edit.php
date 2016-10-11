<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('categories.edit_category', $category->title); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/categories/edit/' . $category->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">
		<div class="form-group clearfix <?php if (isset($errors['title'])) {
			echo 'has-error';
		} else {
			echo '';
		} ?>">
			<label for="fullname" class="col-sm-2 control-label"><?php echo __('posts.title') ?> <span
					class="text-danger">*</span></label>
			<div class="col-sm-10 clearfix">

				<?php echo Form::text('title', Input::previous('title',$category->title), array(
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
		<div class="form-group clearfix <?php if (isset($errors['slug'])) {
			echo 'has-error';
		} else {
			echo '';
		} ?>">
			<label for="fullname" class="col-sm-2 control-label"><?php echo __('categories.slug') ?> <span
					class="text-danger">*</span></label>
			<div class="col-sm-10 clearfix">

				<?php echo Form::text('slug', Input::previous('slug',$category->slug), array(
					'id' => 'label-slug',
					'placeholder' => __('categories.slug'),
					'autocomplete' => 'off',
					'autofocus' => 'true',
					'class' => 'form-control'
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
			<label for="fullname" class="col-sm-2 control-label"><?php echo __('categories.description') ?> <span
					class="text-danger">*</span></label>
			<div class="col-sm-10 clearfix">

				<?php echo Form::textarea('description', Input::previous('description',$category->description), array(
					'id' => 'label-description',
					'placeholder' => __('categories.description'),
					'autocomplete' => 'off',
					'autofocus' => 'true',
					'class' => 'form-control'
				)); ?>
				<?php if (isset($errors['description'])) { ?>
					<p class="help-block"><?php echo $errors['description'][0] ?></p>
				<?php } ?>
			</div>
		</div>
		<div class="form-group text-right" style="padding-right: 15px;">
			<aside class="buttons">
				<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>

				<?php echo Html::link('admin/categories' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>

				<?php echo Html::link('admin/categories/delete/' . $category->id, __('global.delete'), array(
					'class' => 'btn delete red'
				)); ?>

			</aside>

		</div>

	</form>
</section>

<script src="<?php echo asset('anchor/views/assets/js/slug.js'); ?>"></script>

<?php echo $footer; ?>
