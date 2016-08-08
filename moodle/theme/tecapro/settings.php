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
 * Moodle's Clean theme, an example of how to make a Bootstrap theme
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

defined('MOODLE_INTERNAL') || die;
$settings = null;

if (is_siteadmin()) {

    $ADMIN->add('themes', new admin_category('theme_tecapro', 'TECAPRO'));
				
				/* Header Settings */
				$temp = new admin_settingpage('theme_tecapro_header', get_string('headerheading', 'theme_tecapro'));

    // Logo file setting.
    $name = 'theme_tecapro/logo';
    $title = get_string('logo','theme_tecapro');
    $description = get_string('logodesc', 'theme_tecapro');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Custom CSS file.
    $name = 'theme_tecapro/customcss';
    $title = get_string('customcss', 'theme_tecapro');
    $description = get_string('customcssdesc', 'theme_tecapro');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
				
				$ADMIN->add('theme_tecapro', $temp);

    	/* Slideshow Settings Start */
				
				 $temp = new admin_settingpage('theme_tecapro_slideshow', get_string('slideshowheading', 'theme_tecapro'));
    $temp->add(new admin_setting_heading('theme_tecapro_slideshow', get_string('slideshowheadingsub', 'theme_tecapro'),
        format_text(get_string('slideshowdesc', 'theme_tecapro'), FORMAT_MARKDOWN)));
				
				// Display Slideshow.
    $name = 'theme_tecapro/toggleslideshow';
    $title = get_string('toggleslideshow', 'theme_tecapro');
    $description = get_string('toggleslideshowdesc', 'theme_tecapro');
    $yes = get_string('yes');
    $no = get_string('no');
    $default = 0;
    $choices = array(1 => $yes , 0 => $no);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
				
				// Number of slides.
    $name = 'theme_tecapro/numberofslides';
    $title = get_string('numberofslides', 'theme_tecapro');
    $description = get_string('numberofslides_desc', 'theme_tecapro');
    $default = 3;
    $choices = array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => '11',
        12 => '12',
    );
    $temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));
				

    $numberofslides = get_config('theme_tecapro', 'numberofslides');
    for ($i = 1; $i <= $numberofslides; $i++) {
				    
								// This is the descriptor for Slide One
        $name = 'theme_tecapro/slide' . $i . 'info';
        $heading = get_string('slideno', 'theme_tecapro', array('slide' => $i));
        $information = get_string('slidenodesc', 'theme_tecapro', array('slide' => $i));
        $setting = new admin_setting_heading($name, $heading, $information);
        $temp->add($setting);
								
								 // Slide Image.
        $name = 'theme_tecapro/slide' . $i . 'image';
        $title = get_string('slideimage', 'theme_tecapro');
        $description = get_string('slideimagedesc', 'theme_tecapro');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide' . $i . 'image');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Slide Caption.
        $name = 'theme_tecapro/slide' . $i . 'caption';
        $title = get_string('slidecaption', 'theme_tecapro');
        $description = get_string('slidecaptiondesc', 'theme_tecapro');
        $default = get_string('slidecaptiondefault','theme_tecapro', array('slideno' => sprintf('%02d', $i) ));
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);
								
								// Slide Description Text.
								$name = 'theme_tecapro/slide' . $i . 'desc';
								$title = get_string('slidedesc', 'theme_tecapro');
								$description = get_string('slidedesctext', 'theme_tecapro');
								$default = get_string('slidedescdefault','theme_tecapro');
								$setting = new admin_setting_confightmleditor($name, $title, $description, $default);
								$setting->set_updatedcallback('theme_reset_all_caches');
								$temp->add($setting);
								
				}
				
				$ADMIN->add('theme_tecapro', $temp);
				/* Slideshow Settings End*/
				
				/* Footer Settings start */
				
				$temp = new admin_settingpage('theme_tecapro_footer', get_string('footerheading', 'theme_tecapro'));
				
				/* Footer Content */
				$name = 'theme_tecapro/footnote';
    $title = get_string('footnote', 'theme_tecapro');
    $description = get_string('footnotedesc', 'theme_tecapro');
    $default = get_string('footnotedefault', 'theme_tecapro');
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// INFO Link
	
		$name = 'theme_tecapro/infolink';
    $title = get_string('infolink', 'theme_tecapro');
    $description = get_string('infolink_desc', 'theme_tecapro');
    $default = get_string('infolinkdefault', 'theme_tecapro');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// copyright 
	
	$name = 'theme_tecapro/copyright_footer';
    $title = get_string('copyright_footer', 'theme_tecapro');
    $description = '';
    $default = get_string('copyright_default','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
				

				/* Address , Email , Phone No */
				
				$name = 'theme_tecapro/address';
    $title = get_string('address', 'theme_tecapro');
    $description = '';
    $default = get_string('defaultaddress','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
				
				
				$name = 'theme_tecapro/emailid';
    $title = get_string('emailid', 'theme_tecapro');
    $description = '';
    $default = get_string('defaultemailid','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
				
					$name = 'theme_tecapro/phoneno';
    $title = get_string('phoneno', 'theme_tecapro');
    $description = '';
    $default = get_string('defaultphoneno','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
				
					/* Facebook,Pinterest,Twitter,Google+ Settings */
				$name = 'theme_tecapro/fburl';
    $title = get_string('fburl', 'theme_tecapro');
    $description = get_string('fburldesc', 'theme_tecapro');
    $default = get_string('fburl_default','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
				
				$name = 'theme_tecapro/pinurl';
    $title = get_string('pinurl', 'theme_tecapro');
    $description = get_string('pinurldesc', 'theme_tecapro');
    $default = get_string('pinurl_default','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
				
				$name = 'theme_tecapro/twurl';
    $title = get_string('twurl', 'theme_tecapro');
    $description = get_string('twurldesc', 'theme_tecapro');
    $default = get_string('twurl_default','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
				
				$name = 'theme_tecapro/gpurl';
    $title = get_string('gpurl', 'theme_tecapro');
    $description = get_string('gpurldesc', 'theme_tecapro');
    $default = get_string('gpurl_default','theme_tecapro');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $ADMIN->add('theme_tecapro', $temp);
			 /*  Footer Settings end */	
				
				
}
