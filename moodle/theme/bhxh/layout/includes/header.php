<?php global $CFG; ?>
<header id="header">
    <div class="header-main">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-8">
                    <div class="logo">
                        <a href="<?php echo $CFG->wwwroot; ?>">
                            <div class="logo-section">
                                <img src="<?php echo get_logo_url(); ?>" class="site-logo-img" alt="bhxh">
                            </div>
                            <div class="logo-section">
                                <div class="logo-title">
                                    <?php echo get_string('configtitle', 'theme_bhxh'); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <div class="clearfix">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-responsive-collapse" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <?php if ($CFG->branch > "27"): ?>
                                    <?php echo $OUTPUT->user_menu(); ?>
                                <?php endif; ?>
                            </div>
                            <div class="nav-collapse collapse navbar-responsive-collapse clearfix">
                                <?php echo $OUTPUT->custom_menu(); ?>
                                <ul class="nav pull-right">
                                    <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                                    <?php if ($CFG->branch < "28"): ?>
                                        <li class="navbar-text"><?php echo $OUTPUT->login_info() ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isloggedin() && !isguestuser()): ?>
        <?php else: ?>
            <div class="banner">
                <div class="banner-content css">
                    <div class="font d1">Chào mừng đến với</div>
                    <div class="font d2">HỆ THỐNG ĐÀO TẠO TRỰC TUYẾN</div>
                    <div class="font d3">HƯỚNG DỊCH VỤ SOA</div>
                </div>
            </div>
    <?php endif; ?>
</header>
<!--E.O.Header-->
