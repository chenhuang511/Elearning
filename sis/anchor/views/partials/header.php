<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo __('global.manage'); ?><?php echo Config::meta('sitename'); ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo asset('anchor/views/assets/img/favicon.png'); ?>"/>

    <script src="<?php echo asset('anchor/views/assets/js/zepto.js'); ?>"></script>

    <link rel="stylesheet" href="<?php echo asset_url('css/bootstrap.min.css'); ?>">

    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/reset.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/login.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/notifications.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/forms.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/advance.css'); ?>">

    <link rel="stylesheet" href="<?php echo asset_url('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/bhxh.css'); ?>">

    <link rel="stylesheet" media="(max-width: 980px), (max-device-width: 480px)"
          href="<?php echo asset('anchor/views/assets/css/small.css'); ?>">
    <script src="<?php echo asset_url('/js/jquery-3.1.0.min.js'); ?>"></script>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="viewport" content="width=600">
</head>
<body
    class="<?php echo Auth::guest() ? 'login' : 'admin'; ?> <?php echo str_replace('_', '-', Config::app('language')); ?>">
<header class="header navbar navbar-default navbar-fixed-top no-site-messages" id="header">
    <div class="container">
        <div id="logo-area" class="logo-area bhxh-table pull-left">
            <div class="logo-section bhxh-td">
                <a href="<?php echo base_url(); ?>" class="logo">
                    <img src="<?php echo asset_url('img/site-logo.png'); ?>" alt="qldt">
                </a>
            </div>
            <div class="logo-section bhxh-td">
                <p class="logo-title">
                    <?php echo site_name(); ?>
                </p>
            </div>
        </div>
        <div class="right-menu pull-right">
            <ul class="nav nav-pills" role="tablist">
                <?php if (Auth::user()): ?>
                    <li class="username li-square">
                        <a href="#" class="fa fa-user"> <?= user_authed_name() ?></a>
                    </li>
                    <li class="logout li-square">
                        <?php echo Html::link('admin/logout', __('global.logout'), array('class' => 'fa fa-sign-out')); ?>
                    </li>
                <?php else: ?>
                    <li class="logout li-square">
                        <a href="<?php echo Uri::to('admin/login'); ?>"><i class="fa fa-sign-in" aria-hidden="true"></i>Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>
<?php if (Auth::user()): ?>
    <nav id="main-nav" class="no-site-messages navbar-inverse nav collapse navbar-collapse nav-main main-nav">
        <div class="container">
            <div class="bhxh-nav-home clearfix">
                <ul id="dropdown-nav" class="nav navbar-nav">
                    <?php $menu = array('panel', 'posts', 'comments', 'pages', 'categories', 'users', 'students', 'schools', 'instructor', 'contract', 'extend', 'advance', 'grade', 'courses', 'curriculum'); ?>
                    <?php foreach ($menu as $url): ?>
                        <li <?php if (strpos(Uri::current(), $url) !== false) echo 'class="home active dropdown-nav-home"'; ?>>
                            <?php if ($url === 'curriculum') { ?>
                                <a href="<?php echo Uri::to('admin/' . $url . '/add'); ?>">
                                    <?php echo ucfirst(__($url . '.' . $url)); ?>
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo Uri::to('admin/' . $url); ?>">
                                    <?php echo ucfirst(__($url . '.' . $url)); ?>
                                </a>
                            <?php } ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>
<div class="bhxh-body">
    <div class="container">

