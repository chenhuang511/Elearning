<?php
$footnote = theme_bhxh_get_setting('footnote', 'format_html');

$fburl = theme_bhxh_get_setting('fburl');
$pinurl = theme_bhxh_get_setting('pinurl');
$twurl = theme_bhxh_get_setting('twurl');
$gpurl = theme_bhxh_get_setting('gpurl');

$address = theme_bhxh_get_setting('address');
$emailid = theme_bhxh_get_setting('emailid');
$phoneno = theme_bhxh_get_setting('phoneno');
$copyright_footer = theme_bhxh_get_setting('copyright_footer');
$infolink = theme_bhxh_get_setting('infolink');

?>
    <footer id="footer">
        <div class="footer-main">
            <div class="container">
                <div class="row-fluid">
                    <div class="foot-links-wrap">
                        <div class="clearfix">
                            <?php
                            echo $OUTPUT->custom_menu();
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="infoarea">
                        <div class="logo-footer logo">
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
                </div>
            </div>
        </div>
    </footer>
    <!--E.O.Footer-->
<?php echo $OUTPUT->standard_end_of_body_html() ?>
