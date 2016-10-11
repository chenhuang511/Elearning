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
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/open-sans.min.css'); ?>">
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
    <style>
        body {
            padding-top: 50px;
        }
    </style>
</head>
<body
    class="<?php echo Auth::guest() ? 'login' : 'admin'; ?> <?php echo str_replace('_', '-', Config::app('language')); ?>">
<header class="header navbar navbar-default navbar-fixed-top no-site-messages" id="header">
    <div class="container-fluid">
        <div class="main-header">
            <div id="logo-area" class="logo-area">
                <div class="logo-block">
                    <a id="collapse_menu" href="#" style="display: none;"><i class="fa fa-bars" aria-hidden="true"></i></a>
                    <span class="logo-title">
                    <?php echo site_name(); ?>
                </span>
                </div>
            </div>
            <div class="auth-area">
                <ul class="logon-block pull-right">
                    <?php if (Auth::user()): ?>
                        <li class="username">
                            <i class="fa fa-user" aria-hidden="true"></i> Xin chào, <a
                                href="#"> <?= user_authed_name() ?></a>
                        </li>
                        <li class="divided">|</li>
                        <li class="logout">
                            <a href="/admin/logout"><?php echo __('global.logout') ?> <i class="fa fa-sign-out"></i></a>
                        </li>
                    <?php else: ?>
                        <li class="logout">
                            <a href="<?php echo Uri::to('admin/login'); ?>"><i class="fa fa-sign-in"
                                                                               aria-hidden="true"></i>Đăng
                                nhập</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="bhxh-body">
    <div class="clearfix main-body">
        <div id="main_menu" class="col-sm-3">
            <div class="clearfix text-right expanded-menu">
                <a id="expand_menu" href="#"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
            </div>
            <?php if (Auth::user()): ?>
                <div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingCourse">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordionMenu"
                                   href="#collapseCourse"
                                   aria-expanded="true" aria-controls="collapseCourse">
                                    <i class="fa fa-cog" aria-hidden="true"></i> Quản lý khóa học
                                </a>
                            </h4>
                        </div>
                        <div id="collapseCourse"
                             class="panel-collapse collapse in" role="tabpanel"
                             aria-labelledby="headingCourse">
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
                        <div class="panel-heading" role="tab" id="headingPost">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionMenu"
                                   href="#collapsePost" aria-expanded="false" aria-controls="collapsePost">
                                    <i class="fa fa-book" aria-hidden="true"></i> Quản lý bài viết
                                </a>
                            </h4>
                        </div>
                        <div id="collapsePost" class="panel-collapse collapse" role="tabpanel"
                             aria-labelledby="headingPost">
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
                        <div class="panel-heading" role="tab" id="headingSystem">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionMenu"
                                   href="#collapseSystem" aria-expanded="false" aria-controls="collapseSystem">
                                    <i class="fa fa-users" aria-hidden="true"></i> Quản lý tài khoản hệ thống
                                </a>
                            </h4>
                        </div>
                        <div id="collapseSystem" class="panel-collapse collapse" role="tabpanel"
                             aria-labelledby="headingSystem">
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
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingRoom">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionMenu"
                                   href="#collapseRoom" aria-expanded="false" aria-controls="collapseRoom">
                                    <i class="fa fa-users" aria-hidden="true"></i> Quản lý Phòng, thiết bị
                                </a>
                            </h4>
                        </div>
                        <div id="collapseRoom" class="panel-collapse collapse" role="tabpanel"
                             aria-labelledby="headingRoom">
                            <div class="panel-body">
                                <ul class="mnu-lst">
                                    <li><a href="<?php echo Uri::to('admin/rooms'); ?>"><i class="fa fa-caret-right"
                                                                                           aria-hidden="true"></i>
                                            Danh sách phòng học</a></li>
                                    <li><a href="<?php echo Uri::to('admin/equipment/add/room'); ?>"><i
                                                class="fa fa-caret-right"
                                                aria-hidden="true"></i>
                                            Tạo phòng học mới</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div id="main_content" class="col-sm-9">

