<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle's tecapro theme, an example of how to make a Bootstrap theme
 *
 * DO NOT MODIFY THIS THEME!
 * COPY IT FIRST, THEN RENAME THE COPY AND MODIFY IT INSTEAD.
 *
 * For full information about creating Moodle themes, see:
 * http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   theme_tecapro
 * @copyright 2015 Nephzat Dev Team, nephzat.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Get the HTML for the settings bits.
$html = theme_tecapro_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600,700italic,400italic,600italic&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>

<div class="container-fluid">
   <?php
         $toggleslideshow = theme_tecapro_get_setting('toggleslideshow');
        if ($toggleslideshow == 1) {
            require_once(dirname(__FILE__) . '/includes/slideshow.php');
        }else{
								    echo "<br/><br/>";
								}
        ?>
</div>
<!--Slider-->

<div id="page" class="container-fluid">

    <header id="page-header" class="clearfix">
        <?php echo $html->heading; ?>
        <div id="page-navbar" class="clearfix">
            <nav class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></nav>
            <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
        </div>
        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
    </header>
    <div id="page-content" class="row">
        <?php
        $context = context_module::instance($COURSE->id);
        $isstudent = !has_capability('moodle/course:manageactivities', $context);
        if ($isstudent) { ?>
            <?php
            $activenode = $PAGE->navigation->find_active_node();
            $keynode = $activenode->key;
            $keyparentnode = $activenode->parent->key;
        ?>
            <div class="col-sm-12">
                <div class="tab-course-container container">
                    <ul id="coursetabs" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a id="coursewaretab" role="tab" data-toggle="tab" aria-controls="tab-content-1" aria-expanded="true" href="#tab-content-1">Tổng quan</a>
                        </li>
                        <li role="presentation" class="">
                            <a id="courseinfotab" role="tab" data-toggle="tab" aria-controls="tab-content-2" aria-expanded="false" href="#tab-content-2">Thông tin</a>
                        </li>
                        <li role="presentation" class="">
                            <a id="forumtab" role="tab" data-toggle="tab" aria-controls="tab-content-3" aria-expanded="false" href="#tab-content-3">Diễn đàn</a>
                        </li>
                        <li role="presentation" class="">
                            <a id="wikitab" role="tab" data-toggle="tab" aria-controls="tab-content-4" aria-expanded="false" href="#tab-content-4">Wiki</a>
                        </li>
                        <li role="presentation" class="">
                            <a id="chattab" role="tab" data-toggle="tab" aria-controls="tab-content-5" aria-expanded="false" href="#tab-content-5">Chat</a>
                        </li>
                        <li role="presentation" class="">
                            <a id="processtab" role="tab" data-toggle="tab" aria-controls="tab-content-6" aria-expanded="false" href="#tab-content-6">Tiến độ học</a>
                        </li>
                    </ul>
                    <div id="courseTabContent" class="tab-content">
                        <div role="tabpanel" id="tab-content-1" class="tab-pane fade active in" aria-labelledby="coursewaretab-tab">
                            <div class="courseware-block">
                                <div class="row">
                                    <div class="col-sm-3 courseware-menu">
                                        <?php
                                        $course = get_remote_course_content($COURSE->remoteid);
                                        ?>
                                        <div class="panel-group" id="section-menu" role="tablist" aria-multiselectable="true">
                                            <?php
                                            global $CFG; ?>

                                            <?php foreach ($course['content'] as $key => $section) {
                                                $heading = 'mod-' . $section->id;
                                                $collapse = 'collapseMod' . $section->id;
                                                ?>

                                                <?php if ($section->modules) {
                                                    ?>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading" role="tab" id="<?php echo $heading ?>">
                                                            <h4 class="panel-title">
                                                                <a id="csec-<?php echo $section->id ?>" role="button" data-toggle="collapse"
                                                                   data-parent="#section-menu"
                                                                   href="#<?php echo $collapse ?>"
                                                                   aria-expanded="false" aria-controls="<?php echo $collapse ?>"
                                                                   class="collapsed'" data-summary="<?php echo htmlspecialchars($section->summary) ?>">
                                                                    <i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $section->name ?> </a>
                                                            </h4>
                                                        </div>
                                                        <div id="<?php echo $collapse ?>" class="panel-collapse collapse
                                                        <?php
                                                        if($section->id == $keyparentnode){
                                                            echo 'in';
                                                        }
                                                        ?>
                                                        " role="tabpanel"
                                                             aria-labelledby="<?php echo $heading ?>"
                                                             aria-expanded="false">
                                                            <div class="panel-body">
                                                                <?php foreach ($section->modules as $keymod => $module) {
                                                                    if ($module->modname !== 'forum' && $module->modname !== 'wiki') {
                                                                        if ($module->modname === 'label') {
                                                                            ?>
                                                                            <a id="mlabel-<?php echo $module->id ?>" class="sublink"
                                                                               href="#mlabel-<?php echo $module->id ?>"
                                                                               data-description="<?php echo htmlspecialchars($module->description) ?>">
                                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                                <?php echo $module->name ?>
                                                                            </a>
                                                                            <?php
                                                                        } else { ?>
                                                                            <a class="sublink<?php
                                                                            if($module->id == $keynode) {
                                                                                echo ' sublink-active';
                                                                            }
                                                                            ?>
                                                                            "
                                                                               href="<?php echo $CFG->wwwroot . '/mod/' . $module->modname . '/remote/view.php?id=' . $module->id; ?>"
                                                                               >
                                        <span
                                            class="icon-bxh icon-<?php echo $module->modname; ?>"></span><?php echo $module->name; ?>
                                                                            </a>
                                                                        <?php } ?>
                                                                    <?php }
                                                                } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } ?>
                                        </div>

                                    </div>
                                    <div id="<?php echo $regionbsid ?>" class="col-md-9">
                                        <?php
                                        echo $OUTPUT->course_content_header();
                                        echo $OUTPUT->main_content();
                                        echo $OUTPUT->course_content_footer();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-content-2" class="tab-pane fade">content</div>
                        <div role="tabpanel" id="tab-content-3" class="tab-pane fade">content</div>
                        <div role="tabpanel" id="tab-content-4" class="tab-pane fade">content</div>
                        <div role="tabpanel" id="tab-content-5" class="tab-pane fade">content</div>
                        <div role="tabpanel" id="tab-content-6" class="tab-pane fade">content</div>
                    </div>
                </div>
            </div>
        <?php
        } else {
            ?>

            <?php echo $OUTPUT->blocks('side-pre', 'col-md-3'); ?>
            <div id="<?php echo $regionbsid ?>" class="col-md-9">
                <?php
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_footer();
                ?>
            </div>
            <?php
        }
        ?>
    </div>

</div>

<?php  require_once(dirname(__FILE__) . '/includes/footer.php');  ?>
</body>
</html>
