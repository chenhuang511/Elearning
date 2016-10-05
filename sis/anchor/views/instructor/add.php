<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('instructor.create_instructor'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>
	<?php if(Auth::admin()) : ?>

	<form method="post" action="<?php echo Uri::to('admin/instructor/add'); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="half split">
			<p>
				<label for="label-firstname"><?php echo __('instructor.first_name'); ?>:</label>
				<?php echo Form::text('firstname', Input::previous('firstname'), array('id' => 'label-firstname')); ?>
			</p>
			<p>
				<label for="label-lastname"><?php echo __('instructor.last_name'); ?>:</label>
				<?php echo Form::text('lastname', Input::previous('lastname'), array('id' => 'label-lastname')); ?>
			</p>
			<p>
				<label for="label-birthday"><?php echo __('instructor.birthday'); ?>:</label>
				<?php echo Form::date('birthday', Input::previous('birthday'), array('id' => 'label-birthday')); ?>
			</p>
		</fieldset>

		<fieldset class="half split">
			<p>
				<label for="label-email"><?php echo __('instructor.email'); ?>:</label>
				<?php echo Form::text('email', Input::previous('email'), array('id' => 'label-email')); ?>
			</p>
			<p>
				<label for="label-subject"><?php echo __('instructor.subject'); ?>:</label>
				<?php echo Form::text('subject', Input::previous('subject'), array('id' => 'label-subject')); ?>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.create'), array('class' => 'btn', 'type' => 'submit')); ?>

			<?php echo Html::link('admin/instructor' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>
		</aside>
	</form>
	<?php else : ?>
		<p>You do not have the required privileges to add instructor, you must be an Administrator. Please contact the Administrator of the site if you are supposed to have these privileges.</p>
		<br><a class="btn" href="<?php echo Uri::to('admin/instructor'); ?>">Go back</a>
	<?php endif; ?>
</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
