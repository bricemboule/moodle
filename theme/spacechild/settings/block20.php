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

$page = new admin_settingpage('theme_spacechild_block20', get_string('settingsblock20', 'theme_spacechild'));

$name = 'theme_spacechild/displayblock20';
$title = get_string('turnon', 'theme_spacechild');
$description = get_string('displayblock20_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title .
    '<span class="badge badge-sq badge-dark ml-2">Block #20</span>', $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block20class';
$title = get_string('additionalclass', 'theme_spacechild');
$description = get_string('additionalclass_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/herologo';
$title = get_string('herologo', 'theme_spacechild');
$description = get_string('herologo_desc', 'theme_spacechild');
$opts = ['accepted_types' => ['.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'], 'maxfiles' => 1];
$setting = new admin_setting_configstoredfile($name, $title, $description, 'herologo', 0, $opts);
$page->add($setting);

$name = 'theme_spacechild/herologotxt';
$title = get_string('herologotxt', 'theme_spacechild');
$description = get_string('herologotxt_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/imgslidesonly';
$title = get_string('imgslidesonly', 'theme_spacechild');
$description = get_string('imgslidesonly_desc', 'theme_spacechild');
$setting = new admin_setting_configcheckbox($name, $title, $description, 0);
$page->add($setting);

$name = 'theme_spacechild/sliderloop';
$title = get_string('sliderloop', 'theme_spacechild');
$description = get_string('sliderloop_desc', 'theme_spacechild');
$setting = new admin_setting_configcheckbox($name, $title, $description, 1);
$page->add($setting);

$name = 'theme_spacechild/sliderintervalenabled';
$title = get_string('sliderintervalenabled', 'theme_spacechild');
$description = get_string('sliderintervalenabled_desc', 'theme_spacechild');
$setting = new admin_setting_configcheckbox($name, $title, $description, 0);
$page->add($setting);

$name = 'theme_spacechild/sliderinterval';
$title = get_string('sliderinterval', 'theme_spacechild');
$description = get_string('sliderinterval_desc', 'theme_spacechild');
$default = '6000';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);
$settings->hide_if(
    'theme_spacechild/sliderinterval',
    'theme_spacechild/sliderintervalenabled',
    'notchecked'
);

$name = 'theme_spacechild/sliderclickable';
$title = get_string('sliderclickable', 'theme_spacechild');
$description = get_string('sliderclickable_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/rtlslider';
$title = get_string('rtlslider', 'theme_spacechild');
$description = get_string('rtlslider_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/slidercount';
$title = get_string('slidercount', 'theme_spacechild');
$description = get_string('slidercount_desc', 'theme_spacechild');
$default = 1;
$options = [];
for ($i = 1; $i < 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$slidercount = get_config('theme_spacechild', 'slidercount');

if (!$slidercount) {
    $slidercount = 1;
}

for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
    $name = 'theme_spacechild/hblock1slide' . $sliderindex;
    $heading = get_string('hblock1slide', 'theme_spacechild');
    $setting = new admin_setting_heading($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $heading, format_text(get_string('hblock1slide_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
    $page->add($setting);

    $fileid = 'sliderimage' . $sliderindex;
    $name = 'theme_spacechild/sliderimage' . $sliderindex;
    $title = get_string('sliderimage', 'theme_spacechild');
    $description = get_string('sliderimage_desc', 'theme_spacechild');
    $opts = ['accepted_types' => ['.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'], 'maxfiles' => 1];
    $setting = new admin_setting_configstoredfile($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $fileid, 0, $opts);
    $page->add($setting);

    $name = 'theme_spacechild/sliderurl' . $sliderindex;
    $title = get_string('sliderurl', 'theme_spacechild');
    $description = get_string('sliderurl_desc', 'theme_spacechild');
    $default = '';
    $setting = new admin_setting_configtext($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $default);
    $page->add($setting);

    $name = 'theme_spacechild/slidertitle' . $sliderindex;
    $title = get_string('slidertitle', 'theme_spacechild');
    $description = get_string('slidertitle_desc', 'theme_spacechild');
    $default = '';
    $setting = new admin_setting_configtextarea($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $default);
    $page->add($setting);

    $name = 'theme_spacechild/slidersubtitle' . $sliderindex;
    $title = get_string('slidersubtitle', 'theme_spacechild');
    $description = get_string('slidersubtitle_desc', 'theme_spacechild');
    $default = '';
    $setting = new admin_setting_configtextarea($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $default);
    $page->add($setting);

    $name = 'theme_spacechild/slidercap' . $sliderindex;
    $title = get_string('slidercaption', 'theme_spacechild');
    $description = get_string('slidercaption_desc', 'theme_spacechild');
    $default = '';
    $setting = new admin_setting_configtextarea($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $default);
    $page->add($setting);

    $name = 'theme_spacechild/sliderhtml' . $sliderindex;
    $title = get_string('sliderhtml', 'theme_spacechild');
    $description = get_string('sliderhtml_desc', 'theme_spacechild');
    $setting = new admin_setting_configtextarea($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, '', PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_spacechild/sliderheight' . $sliderindex;
    $title = get_string('sliderheight', 'theme_spacechild');
    $description = get_string('sliderheight_desc', 'theme_spacechild');
    $default = '';
    $setting = new admin_setting_configtext($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $default);
    $page->add($setting);

    $name = 'theme_spacechild/slidebackdrop' . $sliderindex;
    $title = get_string('slidebackdrop', 'theme_spacechild');
    $description = get_string('slidebackdrop_desc', 'theme_spacechild');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $default);
    $page->add($setting);
}

$settings->add($page);
