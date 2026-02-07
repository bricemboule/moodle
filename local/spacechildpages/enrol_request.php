<?php
require_once('../../config.php');

use local_spacechildpages\form\enrol_request_form;
use core_course_category;

require_once($CFG->dirroot . '/local/spacechildpages/classes/form/enrol_request_form.php');

$courseid = optional_param('courseid', 0, PARAM_INT);
$flow = optional_param('flow', '', PARAM_ALPHA);
$sent = optional_param('sent', 0, PARAM_BOOL);
$course = null;
if ($courseid) {
    $course = $DB->get_record('course', ['id' => $courseid], '*', IGNORE_MISSING);
    if (empty($course) || (int)$course->id === SITEID) {
        $courseid = 0;
        $course = null;
    }
}

$PAGE->set_context(context_system::instance());
$pageparams = ['courseid' => $courseid];
if ($flow !== '') {
    $pageparams['flow'] = $flow;
}
if (!empty($sent)) {
    $pageparams['sent'] = 1;
}
$PAGE->set_url(new moodle_url('/local/spacechildpages/enrol_request.php', $pageparams));
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

$isloggedin = isloggedin() && !isguestuser();
$showchoice = $flow === '' && !$sent;
$mode = ($flow === 'existing') ? 'existing' : 'full';

$formurl = new moodle_url('/local/spacechildpages/enrol_request.php', $pageparams);
$mform = new enrol_request_form($formurl, [
    'courseid' => $courseid,
    'courses' => $courseoptions,
    'course_locked' => !empty($courseid),
    'mode' => $mode,
    'is_logged_in' => $isloggedin,
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

    if ($mode === 'existing') {
        $resolveduser = null;
        if ($isloggedin) {
            $resolveduser = $USER;
        } else {
            $emailinput = clean_param((string)($data->email ?? ''), PARAM_EMAIL);
            if ($emailinput !== '') {
                $emailinput = core_text::strtolower($emailinput);
                $sql = "SELECT id, email, deleted, firstname, lastname, phone1, phone2, institution, department
                          FROM {user}
                         WHERE LOWER(email) = ?
                           AND mnethostid = ?";
                $users = $DB->get_records_sql($sql, [$emailinput, $CFG->mnet_localhost_id], 0, 2);
                if (count($users) === 1) {
                    $candidate = reset($users);
                    if (empty($candidate->deleted)) {
                        $resolveduser = \core_user::get_user($candidate->id, '*', IGNORE_MISSING);
                        if ($resolveduser && !empty($resolveduser->deleted)) {
                            $resolveduser = null;
                        }
                    }
                } else if (count($users) > 1) {
                    redirect(
                        new moodle_url('/local/spacechildpages/enrol_request.php', ['courseid' => $courseid, 'flow' => 'existing']),
                        get_string('enrolrequest:existing_email_duplicate', 'local_spacechildpages'),
                        null,
                        \core\output\notification::NOTIFY_ERROR
                    );
                }
            }
        }

        if (!$resolveduser) {
            redirect(
                new moodle_url('/local/spacechildpages/enrol_request.php', ['courseid' => $courseid, 'flow' => 'existing']),
                get_string('enrolrequest:existing_email_notfound', 'local_spacechildpages'),
                null,
                \core\output\notification::NOTIFY_ERROR
            );
        }

        $fullname = fullname($resolveduser);
        $email = trim((string)$resolveduser->email);
        $phone = trim((string)($resolveduser->phone1 ?? $resolveduser->phone2 ?? ''));
        $organisation = trim((string)($resolveduser->institution ?? ''));
        $position = trim((string)($resolveduser->department ?? ''));
        $userid = (int)$resolveduser->id;
    } else {
        $fullname = trim((string)$data->fullname);
        $email = trim((string)$data->email);
        $phone = trim((string)($data->phone ?? ''));
        $organisation = trim((string)$data->organisation);
        $position = trim((string)($data->position ?? ''));
        $userid = $isloggedin ? (int)$USER->id : 0;
    }

    $record = (object) [
        'courseid' => $selectedcourse ? (int)$selectedcourse->id : 0,
        'userid' => $userid ?? 0,
        'fullname' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'organisation' => $organisation,
        'position' => $position,
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

    try {
        $emailsent = email_to_user($admin, $support, $subject, $message);
        if (!$emailsent) {
            error_log('[SPACECHILDPAGES] ❌ Échec envoi email admin (demande inscription).');
            debugging('Failed to send enrol request email to admin.', DEBUG_DEVELOPER);
        }
    } catch (Exception $e) {
        error_log('[SPACECHILDPAGES] ❌ Exception envoi email admin (demande inscription): ' . $e->getMessage());
        debugging('Exception sending enrol request email: ' . $e->getMessage(), DEBUG_DEVELOPER);
    }

    redirect(
        new moodle_url('/local/spacechildpages/enrol_request.php', ['courseid' => $courseid, 'sent' => 1]),
        get_string('enrolrequest:sent', 'local_spacechildpages')
    );
}

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
if ($showchoice) {
    echo html_writer::tag(
        'div',
        get_string('enrolrequest:existing_question', 'local_spacechildpages'),
        ['class' => 'sc-enrol-request__intro']
    );
} else {
    echo html_writer::tag(
        'div',
        get_string('enrolrequest:intro', 'local_spacechildpages'),
        ['class' => 'sc-enrol-request__intro']
    );
}

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

$flowparams = ['courseid' => $courseid];
if ($showchoice) {
    $yesurl = new moodle_url('/local/spacechildpages/enrol_request.php', $flowparams + ['flow' => 'existing']);
    $nourl = new moodle_url('/local/spacechildpages/enrol_request.php', $flowparams + ['flow' => 'new']);

    echo html_writer::start_tag('div', ['class' => 'sc-enrol-request__choice']);
    echo html_writer::link(
        $yesurl,
        get_string('enrolrequest:existing_yes', 'local_spacechildpages'),
        ['class' => 'sc-enrol-request__choice-btn sc-enrol-request__choice-btn--primary']
    );
    echo html_writer::link(
        $nourl,
        get_string('enrolrequest:existing_no', 'local_spacechildpages'),
        ['class' => 'sc-enrol-request__choice-btn sc-enrol-request__choice-btn--ghost']
    );
    echo html_writer::end_tag('div');

    echo html_writer::end_tag('div');
    echo $OUTPUT->footer();
    exit;
}

$mform->display();

echo html_writer::end_tag('div');
echo $OUTPUT->footer();
