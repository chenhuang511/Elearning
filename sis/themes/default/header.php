<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo page_title('Page can’t be found'); ?> - <?php echo site_name(); ?></title>

		<meta name="description" content="<?php echo site_description(); ?>">

		<link rel="stylesheet" href="<?php echo asset_url('/css/bootstrap.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo theme_url('/css/reset.css'); ?>">
		<link rel="stylesheet" href="<?php echo theme_url('/css/style.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset_url('css/bhxh.css'); ?>">
		<link rel="stylesheet" href="<?php echo theme_url('/css/small.css'); ?>" media="(max-width: 400px)">
		<link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/font-awesome.min.css'); ?>">
		<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo rss_url(); ?>">
		<link rel="shortcut icon" href="<?php echo asset_url('img/favicon.png'); ?>">

		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<script>var base = '<?php echo theme_url(); ?>';</script>
		<script src="<?php echo asset_url('/js/zepto.js'); ?>"></script>
		<script src="<?php echo asset_url('/js/jquery-3.1.0.min.js'); ?>"></script>
		<script src="<?php echo asset_url('/js/text-limit.js'); ?>"></script>
	    <meta name="viewport" content="width=device-width">
	    <meta name="generator" content="Anchor CMS">

	    <meta property="og:title" content="<?php echo site_name(); ?>">
	    <meta property="og:type" content="website">
	    <meta property="og:url" content="<?php echo e(current_url()); ?>">
	    <meta property="og:image" content="<?php echo theme_url('img/og_image.gif'); ?>">
	    <meta property="og:site_name" content="<?php echo site_name(); ?>">
	    <meta property="og:description" content="<?php echo site_description(); ?>">

		<?php if(customised()): ?>
		    <!-- Custom CSS -->
    		<style><?php echo article_css(); ?></style>

    		<!--  Custom Javascript -->
    		<script><?php echo article_js(); ?></script>
		<?php endif; ?>
	</head>
	<body class="<?php echo body_class(); ?>">
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
						<?php if(Auth::user()): ?>
							<li class="username li-square">
								<a href="#" class="fa fa-user"> <?= user_authed_name() ?></a>
							</li>
							<li class="logout li-square">
								<?php echo Html::link('admin/logout', __('global.logout'), array('class' => 'fa fa-sign-out')); ?>
							</li>
						<?php else: ?>
							<li class="logout li-square">
								<a href="<?php echo Uri::to('admin/login'); ?>"><i class="fa fa-sign-in" aria-hidden="true"></i><span>Login</span></a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</header>
		<div class="top-menu container">
			<nav id="main" role="navigation">
				<ul>
					<?php if(has_menu_items()):
						while(menu_items()): ?>

						<?php endwhile;
					endif; ?>
				</ul>
			</nav>
		</div>
		<div class="main-wrap">
			<div class="container">

