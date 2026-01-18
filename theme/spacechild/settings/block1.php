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
 * @copyright 2022 Marcin Czaja (https://rosea.io)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */


defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_spacechild_block1', get_string('settingsblock1', 'theme_spacechild'));

$name = 'theme_spacechild/displayblock1';
$title = get_string('turnon', 'theme_spacechild');
$description = get_string('displayblock1_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title .
    '<span class="badge badge-sq badge-dark ml-2">Block #1 (Slider)</span>', $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block1fw';
$title = get_string('blockfw', 'theme_spacechild');
$description = get_string('blockfw_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block1class';
$title = get_string('additionalclass', 'theme_spacechild');
$description = get_string('additionalclass_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block1wrapperalign';
$title = get_string('block1wrapperalign', 'theme_spacechild');
$description = get_string('block1wrapperalign_desc', 'theme_spacechild');
$default = 1;
$choices = [0 => 'Left', 1 => 'Middle', 2 => 'Right'];
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$page->add($setting);

$name = 'theme_spacechild/block1herotitlesize';
$title = get_string('blocktitlesize', 'theme_spacechild');
$description = get_string('blocktitlesize_desc', 'theme_spacechild');
$default = 1;
$choices = [0 => 'Normal', 1 => 'Large', 2 => 'Extra Large'];
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$page->add($setting);

$name = 'theme_spacechild/block1herotitlecolor';
$title = get_string('blocktitlecolor', 'theme_spacechild');
$description = get_string('blocktitlecolor_desc', 'theme_spacechild');
$default = 0;
$choices = [0 => 'White', 1 => 'Black', 2 => 'Gradient'];
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$page->add($setting);

$name = 'theme_spacechild/block1herotitleweight';
$title = get_string('blocktitleweight', 'theme_spacechild');
$description = get_string('blocktitleweight_desc', 'theme_spacechild');
$default = 1;
$choices = [0 => 'Normal', 1 => 'Medium', 2 => 'Bold'];
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$page->add($setting);

$name = 'theme_spacechild/showblock1sliderwrapper';
$title = get_string('showblock1sliderwrapper', 'theme_spacechild');
$description = get_string('showblock1sliderwrapper_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block1sliderwrapperbg';
$title = get_string('block1sliderwrapperbg', 'theme_spacechild');
$description = get_string('block1sliderwrapperbg_desc', 'theme_spacechild');
$setting = new admin_setting_configcolourpicker($name, $title, $description, '');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_spacechild/block1sliderinterval';
$title = get_string('sliderinterval', 'theme_spacechild');
$description = get_string('sliderinterval_desc', 'theme_spacechild');
$default = '6000';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/block1count';
$title = get_string('block1count', 'theme_spacechild');
$description = get_string('block1count_desc', 'theme_spacechild');
$default = 1;
$options = [];
for ($i = 1; $i <= 7; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// If we don't have an slide yet, default to the preset.
$slidercount = get_config('theme_spacechild', 'block1count');

if (!$slidercount) {
    $slidercount = 1;
}

for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
    $name = 'theme_spacechild/hblock1slide' . $sliderindex;
    $heading = get_string('hblock1slide', 'theme_spacechild');
    $title = get_string('hblock1slide_desc', 'theme_spacechild');
    $setting = new admin_setting_heading($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $heading, format_text($title, FORMAT_MARKDOWN));
    $page->add($setting);

    $fileid = 'block1slideimg' . $sliderindex;
    $name = 'theme_spacechild/block1slideimg' . $sliderindex;
    $title = get_string('block1slideimg', 'theme_spacechild');
    $description = get_string('block1slideimg_desc', 'theme_spacechild');
    $opts = ['accepted_types' => ['.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'], 'maxfiles' => 1];
    $setting = new admin_setting_configstoredfile($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, $fileid, 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_spacechild/block1slidetitle' . $sliderindex;
    $title = get_string('block1slidetitle', 'theme_spacechild');
    $description = get_string('block1slidetitle_desc', 'theme_spacechild');
    $setting = new admin_setting_configtext($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, '', PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_spacechild/block1slidesubheading' . $sliderindex;
    $title = get_string('block1slidesubheading', 'theme_spacechild');
    $description = get_string('block1slidesubheading_desc', 'theme_spacechild');
    $setting = new admin_setting_configtext($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, '', PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_spacechild/block1slidesubheading' . $sliderindex;
    $title = get_string('block1slidesubheading', 'theme_spacechild');
    $description = get_string('block1slidesubheading_desc', 'theme_spacechild');
    $setting = new admin_setting_configtext($name, '<span class="rui-admin-no">' .
        $sliderindex .
        '</span>' .
        $title, $description, '', PARAM_TEXT);
    $page->add($setting);

    $name = 'theme_spacechild/block1slidecaption' . $sliderindex;
    $title = get_string('block1slidecaption', 'theme_spacechild');
    $description = get_string('block1slidecaption_desc', 'theme_spacechild');
    $default = '<div class="rui-hero-desc">
    <p>The Space 2 is dedicated only for Moodle 4.0 and later. For Moodle 3.9 - 3.11 there is Space 1.14</p>
    <p class="mt-3 small">Need help with theme customization?<br>Or you want to report a bug?</p>
    </div>
    <div class="rui-hero-btns mt-3">
    <a href="https://1.envato.market/OR707N" target="_blank" class="btn btn-lg btn-light my-1">Get this theme!</a>
    <a href="#" target="_blank" class="btn btn-lg btn-dark my-1 mx-0">Space 1.14 for Moodle 3.9 - 3.11</a>
    </div>';
    $setting = new admin_setting_confightmleditor($name, '<span class="rui-admin-no">' .
    $sliderindex .
    '</span>' .
    $title, $description, $default);
    $page->add($setting);

    $name = 'theme_spacechild/block1slidecss' . $sliderindex;
    $title = get_string('block1slidecss', 'theme_spacechild');
    $description = get_string('block1slidecss_desc', 'theme_spacechild');
    $setting = new admin_setting_configtextarea($name, '<span class="rui-admin-no">' .
    $sliderindex .
    '</span>' .
    $title, $description, '', PARAM_TEXT);
    $page->add($setting);
}

$settings->add($page);
