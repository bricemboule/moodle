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

$page = new admin_settingpage('theme_spacechild_block4', get_string('settingsblock4', 'theme_spacechild'));

$name = 'theme_spacechild/displayblock4';
$title = get_string('turnon', 'theme_spacechild');
$description = get_string('displayblock4_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title .
    '<span class="badge badge-sq badge-dark ml-2">Block #4</span>', $description, $default);
$page->add($setting);

$name = 'theme_spacechild/displayhrblock4';
$title = get_string('displayblockhr', 'theme_spacechild');
$description = get_string('displayblockhr_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);


$name = 'theme_spacechild/block4class';
$title = get_string('additionalclass', 'theme_spacechild');
$description = get_string('additionalclass_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block4introtitle';
$title = get_string('blockintrotitle', 'theme_spacechild');
$description = get_string('blockintrotitle_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block4introcontent';
$title = get_string('blockintrocontent', 'theme_spacechild');
$description = get_string('blockintrocontent_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block4footercontent';
$title = get_string('blockfootercontent', 'theme_spacechild');
$description = get_string('blockfootercontent_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/hblock4';
$heading = get_string('hblock4', 'theme_spacechild');
$setting = new admin_setting_heading($name, $heading, format_text(get_string('hblock4_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/block4htmlcontent';
$title = get_string('blockhtmlcontent', 'theme_spacechild');
$description = get_string('blockhtmlcontent_desc', 'theme_spacechild');
$default = '';
$setting = new space_setting_confightmleditor($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/hblock4_2';
$heading = get_string('hblock4_2', 'theme_spacechild');
$setting = new admin_setting_heading($name, $heading, format_text(get_string('hblock4_2_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/block4count';
$title = get_string('block4count', 'theme_spacechild');
$description = get_string('block4count_desc', 'theme_spacechild');
$default = 1;
$options = [];
for ($i = 1; $i <= 20; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);


$block4count = get_config('theme_spacechild', 'block4count');

if (!$block4count) {
    $block4count = 1;
}

for ($block4index = 1; $block4index <= $block4count; $block4index++) {
    $name = 'theme_spacechild/block4question' . $block4index;
    $title = get_string('block4question', 'theme_spacechild');
    $description = get_string('block4question_desc', 'theme_spacechild');
    $default = '';
    $setting = new admin_setting_configtextarea($name, '<span class="rui-admin-no">' .
        $block4index . '</span>' . $title, $description, $default);

    $page->add($setting);

    $name = 'theme_spacechild/block4answer' . $block4index;
    $title = get_string('block4answer', 'theme_spacechild');
    $description = get_string('block4answer_desc', 'theme_spacechild');
    $default = '';
    $setting = new space_setting_confightmleditor($name, '<span class="rui-admin-no">' .
        $block4index . '</span>' . $title, $description, $default);

    $page->add($setting);
}

$settings->add($page);
