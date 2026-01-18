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
$page = new admin_settingpage('theme_spacechild_settingssidebar-nav', get_string('settingssidebar-nav', 'theme_spacechild'));

$name = 'theme_spacechild/isitemonsitehome';
$title = get_string('isitemonsitehome', 'theme_spacechild');
$description = get_string('isitemonsitehome_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/possitehome';
$title = get_string('possitehome', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 1;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/isitemondashboard';
$title = get_string('isitemondashboard', 'theme_spacechild');
$description = get_string('isitemondashboard_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/posdashboard';
$title = get_string('posdashboard', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 2;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/isitemoncalendar';
$title = get_string('isitemoncalendar', 'theme_spacechild');
$description = get_string('isitemoncalendar_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/poscalendar';
$title = get_string('poscalendar', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 3;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/isitemonprivatefiles';
$title = get_string('isitemonprivatefiles', 'theme_spacechild');
$description = get_string('isitemonprivatefiles_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/posprivatefiles';
$title = get_string('posprivatefiles', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 4;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/isitemoncontentbank';
$title = get_string('isitemoncontentbank', 'theme_spacechild');
$description = get_string('isitemoncontentbank_desc', 'theme_spacechild');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/poscontentbank';
$title = get_string('poscontentbank', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 5;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/isitemonmycourses';
$title = get_string('isitemonmycourses', 'theme_spacechild');
$description = get_string('isitemonmycourses_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/posmycourses';
$title = get_string('posmycourses', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 6;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/customnavitems';
$title = get_string('customnavitems', 'theme_spacechild');
$description = get_string('customnavitems_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

// Custom Item #1.
$name = 'theme_spacechild/hcustomitem1';
$heading = get_string('hcustomitem1', 'theme_spacechild');
$setting = new admin_setting_heading($name, $heading, format_text(get_string('empty_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/iscustomitem1on';
$title = get_string('iscustomitem1on', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/poscustomitem1';
$title = get_string('poscustomitem1', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 7;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/labelcustomitem1';
$title = get_string('labelcustomitem1', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/iconcustomitem1';
$title = get_string('iconcustomitem1', 'theme_spacechild');
$description = get_string('iconcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/urlcustomitem1';
$title = get_string('urlcustomitem1', 'theme_spacechild');
$description = get_string('urlcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/customitemrole1';
$title = get_string('secnavuserrole', 'theme_spacechild');
$description = get_string('secnavuserrole_desc', 'theme_spacechild');
$options = [];
$options[0] = get_string('none', 'theme_spacechild');
$options[1] = get_string('secnavuserrole1', 'theme_spacechild');
$setting = new admin_setting_configselect($name, '<span class="rui-admin-no">1</span>' .
    $title, $description, 0, $options);
$page->add($setting);

// Custom Item #2.
$name = 'theme_spacechild/hcustomitem2';
$heading = get_string('hcustomitem2', 'theme_spacechild');
$setting = new admin_setting_heading($name, $heading, format_text(get_string('empty_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/iscustomitem2on';
$title = get_string('iscustomitem2on', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/poscustomitem2';
$title = get_string('poscustomitem2', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 8;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/labelcustomitem2';
$title = get_string('labelcustomitem2', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/iconcustomitem2';
$title = get_string('iconcustomitem2', 'theme_spacechild');
$description = get_string('iconcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/urlcustomitem2';
$title = get_string('urlcustomitem2', 'theme_spacechild');
$description = get_string('urlcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/customitemrole2';
$title = get_string('secnavuserrole', 'theme_spacechild');
$description = get_string('secnavuserrole_desc', 'theme_spacechild');
$options = [];
$options[0] = get_string('none', 'theme_spacechild');
$options[1] = get_string('secnavuserrole1', 'theme_spacechild');
$setting = new admin_setting_configselect($name, '<span class="rui-admin-no">2</span>' .
    $title, $description, 0, $options);
$page->add($setting);

// Custom Item #3.
$name = 'theme_spacechild/hcustomitem3';
$heading = get_string('hcustomitem3', 'theme_spacechild');
$setting = new admin_setting_heading($name, $heading, format_text(get_string('empty_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/iscustomitem3on';
$title = get_string('iscustomitem3on', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/poscustomitem3';
$title = get_string('poscustomitem3', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 9;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/labelcustomitem3';
$title = get_string('labelcustomitem3', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/iconcustomitem3';
$title = get_string('iconcustomitem3', 'theme_spacechild');
$description = get_string('iconcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/urlcustomitem3';
$title = get_string('urlcustomitem3', 'theme_spacechild');
$description = get_string('iconcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/customitemrole3';
$title = get_string('secnavuserrole', 'theme_spacechild');
$description = get_string('secnavuserrole_desc', 'theme_spacechild');
$options = [];
$options[0] = get_string('none', 'theme_spacechild');
$options[1] = get_string('secnavuserrole1', 'theme_spacechild');
$setting = new admin_setting_configselect($name, '<span class="rui-admin-no">3</span>' .
    $title, $description, 0, $options);
$page->add($setting);

// Custom Item #4.
$name = 'theme_spacechild/hcustomitem4';
$heading = get_string('hcustomitem4', 'theme_spacechild');
$setting = new admin_setting_heading($name, $heading, format_text(get_string('empty_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/iscustomitem4on';
$title = get_string('iscustomitem4on', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/poscustomitem4';
$title = get_string('poscustomitem4', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 10;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/labelcustomitem4';
$title = get_string('labelcustomitem4', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/iconcustomitem4';
$title = get_string('iconcustomitem4', 'theme_spacechild');
$description = get_string('iconcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/urlcustomitem4';
$title = get_string('urlcustomitem4', 'theme_spacechild');
$description = get_string('urlcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/customitemrole4';
$title = get_string('secnavuserrole', 'theme_spacechild');
$description = get_string('secnavuserrole_desc', 'theme_spacechild');
$options = [];
$options[0] = get_string('none', 'theme_spacechild');
$options[1] = get_string('secnavuserrole1', 'theme_spacechild');
$setting = new admin_setting_configselect($name, '<span class="rui-admin-no">4</span>' .
    $title, $description, 0, $options);
$page->add($setting);

// Custom Item #5.
$name = 'theme_spacechild/hcustomitem5';
$heading = get_string('hcustomitem5', 'theme_spacechild');
$setting = new admin_setting_heading($name, $heading, format_text(get_string('empty_desc', 'theme_spacechild'), FORMAT_MARKDOWN));
$page->add($setting);

$name = 'theme_spacechild/iscustomitem5on';
$title = get_string('iscustomitem5on', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/poscustomitem5';
$title = get_string('poscustomitem5', 'theme_spacechild');
$description = get_string('navposition_desc', 'theme_spacechild');
$default = 11;
$options = [];
for ($i = 1; $i <= 11; $i++) {
    $options[$i] = $i;
}
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'theme_spacechild/labelcustomitem5';
$title = get_string('labelcustomitem5', 'theme_spacechild');
$description = get_string('empty_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/iconcustomitem5';
$title = get_string('iconcustomitem5', 'theme_spacechild');
$description = get_string('iconcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/urlcustomitem5';
$title = get_string('urlcustomitem5', 'theme_spacechild');
$description = get_string('urlcustomitem_desc', 'theme_spacechild');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$page->add($setting);

$name = 'theme_spacechild/customitemrole5';
$title = get_string('secnavuserrole', 'theme_spacechild');
$description = get_string('secnavuserrole_desc', 'theme_spacechild');
$options = [];
$options[0] = get_string('none', 'theme_spacechild');
$options[1] = get_string('secnavuserrole1', 'theme_spacechild');
$setting = new admin_setting_configselect($name, '<span class="rui-admin-no">5</span>' .
    $title, $description, 0, $options);
$page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
