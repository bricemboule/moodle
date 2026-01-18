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
 *
 * @package   theme_spacechild
 * @copyright 2022 - 2023 Marcin Czaja (https://rosea.io)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_spacechild_general', get_string('generalsettings', 'theme_spacechild'));

$name = 'theme_spacechild/hintro';
$heading = get_string('hintro', 'theme_spacechild', $a);
$setting = new space_setting_specialsettingheading($name, $heading,
    format_text(get_string('hintro_desc', 'theme_spacechild', $a), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/accessiblelinks';
$title = get_string('accessiblelinks', 'theme_spacechild');
$description = get_string('accessiblelinks_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/accessiblebtnfocus';
$title = get_string('accessiblebtnfocus', 'theme_spacechild');
$description = get_string('accessiblebtnfocus_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/darkmodetheme';
$title = get_string('darkmodetheme', 'theme_spacechild');
$description = get_string('darkmodetheme_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/darkmodefirst';
$title = get_string('darkmodefirst', 'theme_spacechild');
$description = get_string('darkmodefirst_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/sdarkmode';
$title = get_string('sdarkmode', 'theme_spacechild');
$description = get_string('sdarkmode_desc', 'theme_spacechild');
$default = 'Dark Mode';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/slightmode';
$title = get_string('slightmode', 'theme_spacechild');
$description = get_string('slightmode_desc', 'theme_spacechild');
$default = 'Light Mode';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/themeauthor';
$title = get_string('themeauthor', 'theme_spacechild');
$description = get_string('themeauthor_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/backtotop';
$title = get_string('backtotop', 'theme_spacechild');
$description = get_string('backtotop_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

// Video Size.
$name = 'theme_spacechild/forcefwvideo';
$title = get_string('forcefwvideo', 'theme_spacechild');
$description = get_string('forcefwvideo_desc', 'theme_spacechild', $a);
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show hint for switched role.
$name = 'theme_spacechild/showswitchedroleincourse';
$title = get_string('showswitchedroleincoursesetting', 'theme_spacechild');
$description = get_string('showswitchedroleincoursesetting_desc', 'theme_spacechild', $a);
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show hint in hidden courses.
$name = 'theme_spacechild/showhintcoursehidden';
$title = get_string('showhintcoursehiddensetting', 'theme_spacechild');
$description = get_string('showhintcoursehiddensetting_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

// Show hint guest for access.
$name = 'theme_spacechild/showhintcourseguestaccess';
$title = get_string('showhintcoursguestaccesssetting', 'theme_spacechild');
$description = get_string('showhintcourseguestaccesssetting_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

// Show hint for self enrolment without enrolment key.
$name = 'theme_spacechild/showhintcourseselfenrol';
$title = get_string('showhintcourseselfenrolsetting', 'theme_spacechild');
$description = get_string('showhintcourseselfenrolsetting_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

// Unaddable blocks.
// Blocks to be excluded when this theme is enabled in the "Add a block" list: Administration, Navigation, Courses and
// Section links.
$default = 'navigation,settings,course_list,section_links';
$setting = new admin_setting_configtext(
    'theme_spacechild/unaddableblocks',
    get_string('unaddableblocks', 'theme_spacechild'),
    get_string('unaddableblocks_desc', 'theme_spacechild'),
    $default,
    PARAM_TEXT
);
$page->add($setting);

// Google analytics block.
$name = 'theme_spacechild/googleanalytics';
$title = get_string('googleanalytics', 'theme_spacechild');
$description = get_string('googleanalyticsdesc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
