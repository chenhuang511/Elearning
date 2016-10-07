<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo __('global.manage'); ?><?php echo Config::meta('sitename'); ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo asset('anchor/views/assets/img/favicon.png'); ?>"/>
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/login.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/font-awesome.min.css'); ?>">

    <link rel="stylesheet" media="(max-width: 980px), (max-device-width: 480px)"
          href="<?php echo asset('anchor/views/assets/css/small.css'); ?>">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="viewport" content="width=600">
</head>
<body
    class="<?php echo Auth::guest() ? 'login' : 'admin'; ?> <?php echo str_replace('_', '-', Config::app('language')); ?>">
<div class="pen-title">
    <h1>Hệ thống quản lý đào tạo</h1>
</div>
<div class="module form-module">
    <div class="toggle"><i class="fa fa-times fa-pencil"></i>
    </div>
    <div class="form">
        <h2>Đăng nhập hệ thống</h2>
        <?php echo $messages; ?>
        <?php $user = filter_var(Input::previous('user'), FILTER_SANITIZE_STRING); ?>
        <form method="post" action="<?php echo Uri::to('admin/login'); ?>">
            <input name="token" type="hidden" value="<?php echo $token; ?>">
            <?php echo Form::text('user', $user, array(
                'id' => 'label-user',
                'autocapitalize' => 'off',
                'autofocus' => 'true',
                'placeholder' => __('users.username')
            )); ?>
            <?php echo Form::password('pass', array(
                'id' => 'pass',
                'placeholder' => __('users.password'),
                'autocomplete' => 'off'
            )); ?>
            <button type="submit"><?php echo __('global.login'); ?></button>
        </form>
    </div>
    <div class="cta"><a
            href="<?php echo Uri::to('admin/amnesia'); ?>"><?php echo __('users.forgotten_password'); ?></a></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?php echo asset_url('js/bootstrap.min.js'); ?>"></script>
</body>
</html>