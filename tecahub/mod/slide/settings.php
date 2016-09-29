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
 * Url module admin settings and defaults
 *
 * @package    mod_slide
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once("$CFG->libdir/resourcelib.php");

    $displayoptions = resourcelib_get_displayoptions(array(
        RESOURCELIB_DISPLAY_AUTO,
        RESOURCELIB_DISPLAY_EMBED,
        RESOURCELIB_DISPLAY_FRAME,
    ));
    $defaultdisplayoptions = array(
        RESOURCELIB_DISPLAY_AUTO,
        RESOURCELIB_DISPLAY_EMBED,
    );

    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_configtext('slide/framesize',
        get_string('framesize', 'slide'), get_string('configframesize', 'slide'), 130, PARAM_INT));
    $settings->add(new admin_setting_configpasswordunmask('url/secretphrase', get_string('password'),
        get_string('configsecretphrase', 'slide'), ''));
    $settings->add(new admin_setting_configcheckbox('slide/rolesinparams',
        get_string('rolesinparams', 'slide'), get_string('configrolesinparams', 'slide'), false));
    $settings->add(new admin_setting_configmultiselect('slide/displayoptions',
        get_string('displayoptions', 'slide'), get_string('configdisplayoptions', 'slide'),
        $defaultdisplayoptions, $displayoptions));

    //--- modedit defaults -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('slidemodeditdefaults', get_string('modeditdefaults', 'admin'), get_string('condifmodeditdefaults', 'admin')));

    $settings->add(new admin_setting_configcheckbox('slide/printintro',
        get_string('printintro', 'slide'), get_string('printintroexplain', 'slide'), 1));
    $settings->add(new admin_setting_configselect('slide/display',
        get_string('displayselect', 'slide'), get_string('displayselectexplain', 'slide'), RESOURCELIB_DISPLAY_EMBED, $displayoptions));
    $settings->add(new admin_setting_configtext('slide/popupwidth',
        get_string('popupwidth', 'slide'), get_string('popupwidthexplain', 'slide'), 620, PARAM_INT, 7));
    $settings->add(new admin_setting_configtext('slide/popupheight',
        get_string('popupheight', 'slide'), get_string('popupheightexplain', 'slide'), 450, PARAM_INT, 7));
}
