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

$page = new admin_settingpage('theme_spacechild_seo', get_string('seosettings', 'theme_spacechild'));

// Favicon setting.
$name = 'theme_spacechild/favicon';
$title = get_string('favicon', 'theme_spacechild');
$description = get_string('favicon_desc', 'theme_spacechild');
$opts = ['accepted_types' => ['.ico'], 'maxfiles' => 1];
$setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/favicon16';
$title = get_string('favicon16', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$opts = ['accepted_types' => ['.png'], 'maxfiles' => 1];
$setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon16', 0, $opts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/favicon32';
$title = get_string('favicon32', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$opts = ['accepted_types' => ['.png'], 'maxfiles' => 1];
$setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon32', 0, $opts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/faviconsafaritab';
$title = get_string('faviconsafaritab', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$opts = ['accepted_types' => ['.svg'], 'maxfiles' => 1];
$setting = new admin_setting_configstoredfile($name, $title, $description, 'faviconsafaritab', 0, $opts);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/faviconsafaritabcolor';
$title = get_string('faviconsafaritabcolor', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = '#000000';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);


// Apple Touch Icon.
$name = 'theme_spacechild/seoappletouchicon';
$title = get_string('seoappletouchicon', 'theme_spacechild');
$description = get_string('seoappletouchicon_desc', 'theme_spacechild');
$opts = ['accepted_types' => ['.png'], 'maxfiles' => 1];
$setting = new admin_setting_configstoredfile($name, $title, $description, 'seoappletouchicon');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/seometadesc';
$title = get_string('seometadesc', 'theme_spacechild');
$description = get_string('seometadesc_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/seothemecolor';
$title = get_string('seothemecolor', 'theme_spacechild');
$description = get_string('seothemecolor_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);

$settings->add($page);
