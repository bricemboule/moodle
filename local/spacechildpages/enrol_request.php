<?php
require_once('../../config.php');

use local_spacechildpages\form\enrol_request_form;
use core_course_category;

require_once($CFG->dirroot . '/local/spacechildpages/classes/form/enrol_request_form.php');

$courseid = optional_param('courseid', 0, PARAM_INT);
$course = null;
if ($courseid) {
    $course = $DB->get_record('course', ['id' => $courseid], '*', IGNORE_MISSING);
    if (empty($course) || (int)$course->id === SITEID) {
        $courseid = 0;
        $course = null;
    }
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/enrol_request.php', ['courseid' => $courseid]));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title(get_string('enrolrequest:title', 'local_spacechildpages'));
$PAGE->set_heading(get_string('enrolrequest:title', 'local_spacechildpages'));
$PAGE->add_body_class('sc-enrol-request');
$PAGE->requires->css(new moodle_url('/local/spacechildpages/style/enrol_request.css'));

$logourl = $PAGE->theme->setting_file_url('logo', 'logo');
if (empty($logourl)) {
    $logourl = (new moodle_url('/theme/spacechild/images/LOGO.png'))->out(false);
}
$sitename = format_string($SITE->shortname ?: $SITE->fullname);

$dbman = $DB->get_manager();
if (!$dbman->table_exists('local_spacechildpages_enrolreq')) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('enrolrequest:missingtable', 'local_spacechildpages'), 'error');
    echo $OUTPUT->footer();
    exit;
}

$columns = $DB->get_columns('local_spacechildpages_enrolreq');
if (!isset($columns['email'])) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('enrolrequest:missingtable', 'local_spacechildpages'), 'error');
    echo $OUTPUT->footer();
    exit;
}

$defaults = [];
if (isloggedin() && !isguestuser()) {
    $defaults['fullname'] = fullname($USER);
    if (!empty($USER->email)) {
        $defaults['email'] = $USER->email;
    }
}

$courseoptions = [];
$courses = core_course_category::top()->get_courses([
    'recursive' => true,
    'sort' => ['fullname' => 1],
]);
foreach ($courses as $courseitem) {
    if ((int)$courseitem->id === SITEID) {
        continue;
    }
    $courseoptions[$courseitem->id] = format_string($courseitem->fullname);
}
if ($course && !isset($courseoptions[$course->id])) {
    $courseoptions[$course->id] = format_string($course->fullname);
}

$mform = new enrol_request_form(null, [
    'courseid' => $courseid,
    'courses' => $courseoptions,
    'course_locked' => !empty($courseid),
    'defaults' => $defaults,
]);

if ($data = $mform->get_data()) {
    $selectedcourse = null;
    if (!empty($data->courseid)) {
        $selectedcourse = $DB->get_record('course', ['id' => (int)$data->courseid], '*', IGNORE_MISSING);
        if (empty($selectedcourse) || (int)$selectedcourse->id === SITEID) {
            $selectedcourse = null;
        }
    }

    $record = (object) [
        'courseid' => $selectedcourse ? (int)$selectedcourse->id : 0,
        'userid' => (isloggedin() && !isguestuser()) ? (int)$USER->id : 0,
        'fullname' => trim((string)$data->fullname),
        'email' => trim((string)$data->email),
        'phone' => trim((string)($data->phone ?? '')),
        'organisation' => trim((string)$data->organisation),
        'position' => trim((string)($data->position ?? '')),
        'message' => trim((string)($data->message ?? '')),
        'status' => 'pending',
        'timecreated' => time(),
        'timemodified' => time(),
    ];

    $DB->insert_record('local_spacechildpages_enrolreq', $record);

    $admin = get_admin();
    $support = \core_user::get_support_user();
    $subject = get_string('enrolrequest:email_subject', 'local_spacechildpages');
    $coursename = $selectedcourse ? format_string($selectedcourse->fullname) : get_string('enrolrequest:nocourse', 'local_spacechildpages');
    $manageurl = (new moodle_url('/local/spacechildpages/enrol_requests.php'))->out(false);

    $message = get_string('enrolrequest:email_body', 'local_spacechildpages', (object) [
        'fullname' => $record->fullname,
        'email' => $record->email,
        'organisation' => $record->organisation,
        'phone' => $record->phone,
        'position' => $record->position,
        'course' => $coursename,
        'message' => $record->message ?: '-',
        'url' => $manageurl,
    ]);

    email_to_user($admin, $support, $subject, $message);

    redirect(
        new moodle_url('/local/spacechildpages/enrol_request.php', ['courseid' => $courseid, 'sent' => 1]),
        get_string('enrolrequest:sent', 'local_spacechildpages'),
        2
    );
}

$sent = optional_param('sent', 0, PARAM_BOOL);

echo $OUTPUT->header();
$wrapattrs = ['class' => 'sc-enrol-request__wrap'];
echo html_writer::start_tag('div', $wrapattrs);
echo html_writer::tag(
    'div',
    html_writer::empty_tag('img', ['src' => $logourl, 'alt' => $sitename]),
    ['class' => 'sc-enrol-request__logo']
);
echo html_writer::tag(
    'div',
    get_string('enrolrequest:title', 'local_spacechildpages'),
    ['class' => 'sc-enrol-request__title']
);
echo html_writer::tag(
    'div',
    get_string('enrolrequest:intro', 'local_spacechildpages'),
    ['class' => 'sc-enrol-request__intro']
);

if ($course) {
    echo html_writer::tag(
        'div',
        get_string('enrolrequest:course', 'local_spacechildpages', format_string($course->fullname)),
        ['class' => 'sc-enrol-request__course']
    );
}

if ($sent) {
    echo $OUTPUT->notification(get_string('enrolrequest:sent', 'local_spacechildpages'), 'success');
}

$mform->display();

echo html_writer::end_tag('div');
echo $OUTPUT->footer();
