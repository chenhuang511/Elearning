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

<?php
echo $OUTPUT->standard_top_of_body_html();
$context = context_module::instance($COURSE->id);
?>

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
        context_helper::preload_course($COURSE->id);
        $context = context_course::instance($COURSE->id, MUST_EXIST);
        $isstudent = !has_capability('moodle/course:manageactivities', $context);
        if ($isstudent) { ?>
            <?php
            $activenode = $PAGE->navigation->find_active_node();
            if(!is_int($activenode->parent->key)){
                $keyparentnode = isset($activenode->parent->parent->includesectionnum) ? $activenode->parent->parent->includesectionnum : 1;
                $keynode = null;
            } else {
                $keynode = $activenode->key;
                $keyparentnode = $activenode->parent->key;
            }
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
                            <a id="wikitab" role="tab" data-toggle="tab" aria-controls="tab-content-4" aria-expanded="false" href="#tab-content-4">Danh sách sinh viên</a>
                        </li>
                        <li role="presentation" class="">
                            <a id="chattab" role="tab" data-toggle="tab" aria-controls="tab-content-5" aria-expanded="false" href="#tab-content-5">Huy hiệu</a>
                        </li>
                    </ul>
                    <div id="courseTabContent" class="tab-content">
                        <div role="tabpanel" id="tab-content-1" class="tab-pane fade active in" aria-labelledby="coursewaretab-tab">
                            <div class="courseware-block">
                                <div class="row">
                                    <?php
                                    $course = get_remote_course_content($COURSE->remoteid);
                                    $renderer = $PAGE->get_renderer('format_weeks');
                                    ?>
                                    <div class="col-sm-3 courseware-menu">
                                        <div class="panel-group" id="section-menu" role="tablist" aria-multiselectable="true">
                                            <?php
                                            global $CFG; ?>

                                            <?php foreach ($course['content'] as $key => $section) {
                                                if($key == 0) {
                                                    continue;
                                                }
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
                                                                   class="collapsed toggle-down" data-summary="<?php echo htmlspecialchars($section->summary) ?>">
                                                                    <i class="fa
                                                                     <?php
                                                                    echo $section->id == $keyparentnode || $key == $keyparentnode ? 'fa-caret-down' : 'fa-caret-right';
                                                                    ?>
                                                                     icon" aria-hidden="true"></i></a><a href="<?php echo $CFG->wwwroot.'/course/view.php?id=' . $COURSE->id . '&section='. $key ?>">&nbsp;<?php echo $section->name ?> </a>
                                                            </h4>
                                                        </div>
                                                        <div id="<?php echo $collapse ?>" class="panel-collapse collapse
                                                        <?php
                                                        if($section->id == $keyparentnode || $key == $keyparentnode){
                                                            echo 'in';
                                                        }
                                                        ?>
                                                        " role="tabpanel"
                                                             aria-labelledby="<?php echo $heading ?>"
                                                             aria-expanded="false">
                                                            <div class="panel-body">
                                                                <?php foreach ($section->modules as $keymod => $module) {
                                                                    if ($module->modname != 'label' && $module->modname != 'url') {
                                                                        ?>
                                                                        <a class="sublink<?php
                                                                        if($module->id == $keynode) {
                                                                            echo ' sublink-active';
                                                                        }
                                                                        ?>
                                                                            "
                                                                           href="<?php echo $CFG->wwwroot . '/mod/' . $module->modname . '/view.php?id=' . $module->id; ?>"
                                                                        >
                                        <span
                                            class="icon-bxh icon-<?php echo $module->modname; ?>"></span><?php echo $module->name; ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php
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
                        <div role="tabpanel" id="tab-content-2" class="tab-pane fade">
                            <?php
                            $courseinfo = get_remote_course_info($COURSE->remoteid);
                            ?>
                            <section class="container-course-info">
                                <div class="info-wrapper">
                                    <section class="updates col-sm-8">
                                        <h1>
                                            Course Updates &amp; News
                                        </h1>
                                        <section>
                                            <article>
                                                <p><?php echo $courseinfo->courseinfo?></p>
                                            </article>
                                        </section>
                                    </section>
                                    <section class="title col-sm-4">
                                        <h1><?php echo $courseinfo->coursename ?></h1>
                                        <p></p>
                                    </section>
                                </div>
                            </section>
                        </div>

                        <div role="tabpanel" id="tab-content-3" class="tab-pane fade">
                            <?php
                            $renderer->print_single_section_page_student($COURSE, null, null, null, null, 0, false);
                            ?>
                        </div>
                        <div role="tabpanel" id="tab-content-4" class="tab-pane fade">
                            <!--                            list user-->
                            <?php
                            include_once ('includes/list_users.php');
                            ?>
                        </div>
                        <div role="tabpanel" id="tab-content-5" class="tab-pane fade">
                            <?php
                            include_once ('includes/list_badges.php');
                            ?>
                        </div>
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
<script>
    (function ($) {

        var sections = $('a[id^="csec-"]');
        var changeContent = function (element, cnt) {
            window.sectionTimeout = setTimeout(function () {
                $(loading).hide();
            }, 2000);
            // remove now content
            element.empty();
            // add new content
            element.html(cnt);
        };

        var changeIcon = function (el) {
            $.each(sections, function (index, element) {
                if (sections[index] != el) {
                    var ico = $(this).find('i');
                    if (ico.hasClass('fa-caret-down')) {
                        ico.removeClass('fa-caret-down');
                        ico.addClass('fa-caret-right');
                    }
                }
            });
        };

        if (sections) {
            $.each(sections, function (index, element) {
                $(sections[index]).on('click', function () {

                    changeIcon(sections[index]);

                    var ico = $(this).find('i');

                    if (ico.hasClass('fa-caret-right')) {
                        ico.removeClass('fa-caret-right');
                        ico.addClass('fa-caret-down');
                    } else {
                        if (ico.hasClass('fa-caret-down')) {
                            ico.removeClass('fa-caret-down');
                            ico.addClass('fa-caret-right');
                        }
                    }
                });
            });
        }

    })(jQuery)
</script>
</body>
</html>
