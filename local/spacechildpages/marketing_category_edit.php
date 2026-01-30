<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/local/spacechildpages/classes/form/marketing_category_form.php');

admin_externalpage_setup('local_spacechildpages_mcategories');

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_url(new moodle_url('/local/spacechildpages/marketing_category_edit.php', ['id' => $id]));
$PAGE->set_pagelayout('admin');

$record = null;
if ($id) {
    $record = $DB->get_record('local_spacechildpages_mcategories', ['id' => $id], '*', MUST_EXIST);
}

$filemanageroptions = [
    'maxbytes' => 0,
    'maxfiles' => 1,
    'subdirs' => 0,
    'accepted_types' => ['image'],
];

$mform = new \local_spacechildpages\form\marketing_category_form(null, [
    'filemanageroptions' => $filemanageroptions,
]);

$draftitemid = file_get_submitted_draft_itemid('imagefile');
file_prepare_draft_area(
    $draftitemid,
    $context->id,
    'local_spacechildpages',
    'marketingcategoryimage',
    $record ? (int)$record->id : 0,
    $filemanageroptions
);

$formdata = $record ? (object)$record : new stdClass();
$formdata->imagefile = $draftitemid;
$mform->set_data($formdata);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/spacechildpages/marketing_categories.php'));
}

if ($data = $mform->get_data()) {
    $now = time();
    $data->timemodified = $now;

    $recorddata = clone $data;
    unset($recorddata->imagefile);

    if ($record) {
        $recorddata->id = $record->id;
        $DB->update_record('local_spacechildpages_mcategories', $recorddata);
    } else {
        $recorddata->timecreated = $now;
        $recorddata->id = $DB->insert_record('local_spacechildpages_mcategories', $recorddata);
    }

    file_save_draft_area_files(
        $data->imagefile,
        $context->id,
        'local_spacechildpages',
        'marketingcategoryimage',
        $record ? (int)$record->id : (int)$recorddata->id,
        $filemanageroptions
    );

    redirect(
        new moodle_url('/local/spacechildpages/marketing_categories.php'),
        get_string('categorysaved', 'local_spacechildpages'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

$title = $record
    ? get_string('editcategory', 'local_spacechildpages')
    : get_string('addcategory', 'local_spacechildpages');

$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
