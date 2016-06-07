<?php global $CFG; ?>
<header id="header">
    <div class="header-main">
        <div class="container">
            <div class="row-fluid">
                <div class="span8">
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
                <div class="span4">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <div class="container-fluid">
                                <button type="button" data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <?php if ($CFG->branch > "27"): ?>
                                    <?php echo $OUTPUT->user_menu(null, null, false); ?>
                                <?php endif; ?>
                                <div class="nav-collapse collapse navbar-responsive-collapse">
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
    </div>
    <div class="banner">
        <div class="banner-content"></div>
    </div>
</header>
<!--E.O.Header-->
