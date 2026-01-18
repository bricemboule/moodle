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

// Dashboard settings.
$page = new admin_settingpage('theme_spacechild_settingsdashboard', get_string('settingsdashboard', 'theme_spacechild'));

$name = 'theme_spacechild/setdashboardlayout';
$title = get_string('setdashboardlayout', 'theme_spacechild');
$description = get_string('setdashboardlayout_desc', 'theme_spacechild', $a);
$options = [];
$options[1] = get_string('dashboardlayout1', 'theme_spacechild');
$options[2] = get_string('dashboardlayout2', 'theme_spacechild');
$options[3] = get_string('dashboardlayout3', 'theme_spacechild');
$setting = new admin_setting_configselect($name, $title, $description, 2, $options);
$page->add($setting);

$name = 'theme_spacechild/customdcolsize';
$title = get_string('customdcolsize', 'theme_spacechild');
$description = get_string('customdcolsize_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/dashboardblock1';
$title = get_string('dashboardblock1', 'theme_spacechild');
$description = get_string('dashboardblock1_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/dashboardblock2';
$title = get_string('dashboardblock2', 'theme_spacechild');
$description = get_string('dashboardblock2_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$settings->add($page);
