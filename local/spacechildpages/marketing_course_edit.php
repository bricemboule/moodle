<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/local/spacechildpages/classes/form/marketing_course_form.php');

admin_externalpage_setup('local_spacechildpages_mcourses');

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_url(new moodle_url('/local/spacechildpages/marketing_course_edit.php', ['id' => $id]));
$PAGE->set_pagelayout('admin');

$record = null;
if ($id) {
    $record = $DB->get_record('local_spacechildpages_mcourses', ['id' => $id], '*', MUST_EXIST);
}

$mform = new \local_spacechildpages\form\marketing_course_form();

if ($record) {
    $mform->set_data($record);
}

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/spacechildpages/marketing_courses.php'));
}

if ($data = $mform->get_data()) {
    $now = time();
    $data->timemodified = $now;
    if ($record) {
        $data->id = $record->id;
        $DB->update_record('local_spacechildpages_mcourses', $data);
    } else {
        $data->timecreated = $now;
        $DB->insert_record('local_spacechildpages_mcourses', $data);
    }

    redirect(
        new moodle_url('/local/spacechildpages/marketing_courses.php'),
        get_string('coursesaved', 'local_spacechildpages'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

$title = $record
    ? get_string('editcourse', 'local_spacechildpages')
    : get_string('addcourse', 'local_spacechildpages');

$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
