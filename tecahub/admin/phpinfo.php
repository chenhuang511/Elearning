<?php
// phpinfo.php - shows phpinfo for the current server

require_once("../config.php");
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('phpinfo');

echo $OUTPUT->header();

echo "Bạn không có quyền truy cập";

echo $OUTPUT->footer();

?>