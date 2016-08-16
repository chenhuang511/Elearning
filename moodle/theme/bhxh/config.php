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
 * Version details
 *
 * @package    theme_bhxh
 * @copyright  2016 NCCSOFT VIETNAM , nccsoft.vn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
	
$THEME->name = 'bhxh';

$THEME->doctype = 'html5';
$THEME->parents = array('bootstrap');
$THEME->sheets = array('custom','theme','font-awesome.min');
$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = array();
$THEME->enable_dock = true;
$THEME->editor_sheets = array();

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'theme_bhxh_process_css';

$THEME->layouts = array(
	// Most backwards compatible layout without the blocks - this is the layout used by default
	'standard' => array(
		'file' => 'course.php',
		'regions' => array('side-pre', 'side-post'),
		'defaultregion' => 'side-pre',
	),
	// The site home page.
	'frontpage' => array(
			'file' => 'frontpage.php',
			'regions' => array('side-pre'),
			'defaultregion' => 'side-pre',
			'options' => array('nonavbar' => true),
	),
	'admin' => array(
		'file' => 'frontpage.php',
		'regions' => array('side-pre'),
		'defaultregion' => 'side-pre',
		'options' => array('fluid' => true),
	),
    'incourse' => array(
        'file' => 'course.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    'course' => array(
        'file' => 'course.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'coursecategory' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    'report' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    'secure' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre'
    ),
);

$THEME->blockrtlmanipulations = array(
    'side-pre' => 'side-post',
    'side-post' => 'side-pre'
);

$THEME->javascripts_footer = array(
    'bootstrapjschild'
);
