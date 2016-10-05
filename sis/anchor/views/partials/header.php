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
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/bootstrap-datetimepicker.min.css'); ?>">

    <link rel="stylesheet" href="<?php echo asset_url('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/bhxh.css'); ?>">

    <link rel="stylesheet" media="(max-width: 980px), (max-device-width: 480px)"
          href="<?php echo asset('anchor/views/assets/css/small.css'); ?>">
    <script src="<?php echo asset_url('/js/jquery-3.1.0.min.js'); ?>"></script>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="viewport" content="width=600">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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
            <ul class="logon-block">
                <?php if (Auth::user()): ?>
                    <li class="username">
                        Xin chào, <a href="#"> <?= user_authed_name() ?></a>
                    </li>
                    <li class="divided">|</li>
                    <li class="logout">
                        <a href="/admin/logout"><?php echo __('global.logout') ?> <i class="fa fa-sign-out"></i></a>
                    </li>
                <?php else: ?>
                    <li class="logout">
                        <a href="<?php echo Uri::to('admin/login'); ?>"><i class="fa fa-sign-in" aria-hidden="true"></i>Đăng
                            nhập</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>
<div class="bhxh-body">
    <div class="collapse-menu" style="display: none;">
        <a id="collapse_menu" href="#"><i class="fa fa-cogs" aria-hidden="true"></i></a>
    </div>
    <div class="clearfix main-body">
        <div id="main_menu" class="col-sm-3">
            <div class="clearfix text-right expanded-menu">
                <a id="expand_menu" href="#"><i class="fa fa-caret-square-o-left" aria-hidden="true"></i></a>
            </div>
            <?php if (Auth::user()): ?>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                   aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fa fa-cog" aria-hidden="true"></i> Quản lý khóa học
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                             aria-labelledby="headingOne">
                            <div class="panel-body">
                                <ul class="mnu-lst">
                                    <li><a href="<?php echo Uri::to('admin/courses'); ?>"><i class="fa fa-caret-right"
                                                                                             aria-hidden="true"></i>
                                            Danh
                                            sách khóa học</a></li>
                                    <li><a href="<?php echo Uri::to('admin/curriculum/add/course'); ?>"><i
                                                class="fa fa-caret-right" aria-hidden="true"></i> Tạo lịch giảng</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                   href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fa fa-book" aria-hidden="true"></i> Quản lý bài viết
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                             aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <ul class="mnu-lst">
                                    <li><a href="<?php echo Uri::to('admin/posts'); ?>"><i class="fa fa-caret-right"
                                                                                           aria-hidden="true"></i> Danh
                                            sách
                                            bài viết</a></li>
                                    <li><a href="<?php echo Uri::to('admin/posts/add'); ?>"><i class="fa fa-caret-right"
                                                                                               aria-hidden="true"></i>
                                            Tạo
                                            bài viết mới</a></li>
                                    <li><a href="<?php echo Uri::to('admin/comments'); ?>"><i class="fa fa-caret-right"
                                                                                              aria-hidden="true"></i>
                                            Danh
                                            sách bình luận</a></li>
                                    <li><a href="<?php echo Uri::to('admin/categories'); ?>"><i
                                                class="fa fa-caret-right"
                                                aria-hidden="true"></i> Quản
                                            lý danh mục</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                   href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <i class="fa fa-users" aria-hidden="true"></i> Quản lý tài khoản hệ thống
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                             aria-labelledby="headingThree">
                            <div class="panel-body">
                                <ul class="mnu-lst">
                                    <li><a href="<?php echo Uri::to('admin/users'); ?>"><i class="fa fa-caret-right"
                                                                                           aria-hidden="true"></i> Quản
                                            lý
                                            người dùng</a></li>
                                    <li><a href="<?php echo Uri::to('admin/students'); ?>"><i class="fa fa-caret-right"
                                                                                              aria-hidden="true"></i>
                                            Quản
                                            lý sinh viên</a></li>
                                    <li><a href="<?php echo Uri::to('admin/instructor'); ?>"><i
                                                class="fa fa-caret-right"
                                                aria-hidden="true"></i> Quản
                                            lý giảng viên</a></li>
                                    <li><a href="<?php echo Uri::to('admin/schools'); ?>"><i class="fa fa-caret-right"
                                                                                             aria-hidden="true"></i>
                                            Quản
                                            lý Trường học</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div id="main_content" class="col-sm-9">

