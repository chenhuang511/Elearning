<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo __('global.manage'); ?> <?php echo Config::meta('sitename'); ?></title>
	<link rel="shortcut icon" type="image/png" href="<?php echo asset('anchor/views/assets/img/favicon.png'); ?>" />

	<script src="<?php echo asset('anchor/views/assets/js/zepto.js'); ?>"></script>

	<link rel="stylesheet" href="<?php echo theme_url('/css/bootstrap.min.css'); ?>">

	<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/reset.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/admin.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/login.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/notifications.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/forms.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/font-awesome.min.css'); ?>">

	<link rel="stylesheet" href="<?php echo theme_url('/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo theme_url('/css/bhxh.css'); ?>">

	<link rel="stylesheet" media="(max-width: 980px), (max-device-width: 480px)" href="<?php echo asset('anchor/views/assets/css/small.css'); ?>">
	<script src="<?php echo theme_url('/js/jquery-3.1.0.min.js'); ?>"></script>
	<meta http-equiv="X-UA-Compatible" content="chrome=1">
	<meta name="viewport" content="width=600">
</head>
<body class="<?php echo Auth::guest() ? 'login' : 'admin'; ?> <?php echo str_replace('_','-',Config::app('language')); ?>">
<section class="login login-wrapper">
	<div class="login-content">
		<?php echo $messages; ?>
		<?php $user = filter_var(Input::previous('instructor'), FILTER_SANITIZE_STRING); ?>
		<form method="post" action="<?php echo Uri::to('admin/login'); ?>">

			<input name="token" type="hidden" value="<?php echo $token; ?>">

			<fieldset>
				<p><label for="label-user"><?php echo __('instructor.username'); ?>:</label>
					<?php echo Form::text('instructor', $user, array(
						'id' => 'label-user',
						'autocapitalize' => 'off',
						'autofocus' => 'true',
						'placeholder' => __('instructor.username')
					)); ?></p>

				<p><label for="label-pass"><?php echo __('instructor.password'); ?>:</label>
					<?php echo Form::password('pass', array(
						'id' => 'pass',
						'placeholder' => __('instructor.password'),
						'autocomplete' => 'off'
					)); ?></p>

				<p class="buttons"><a href="<?php echo Uri::to('admin/amnesia'); ?>"><?php echo __('instructor.forgotten_password'); ?></a>
					<button type="submit"><?php echo __('global.login'); ?></button></p>
			</fieldset>
		</form>
	</div>
</section>
</body>
</html>