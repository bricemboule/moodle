<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/enrollib.php');

admin_externalpage_setup('local_spacechildpages_enrolrequests');

$context = context_system::instance();
require_capability('moodle/site:config', $context);

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

$action = optional_param('action', '', PARAM_ALPHA);
$id = optional_param('id', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if (!empty($action) && !empty($id)) {
    $request = $DB->get_record('local_spacechildpages_enrolreq', ['id' => $id], '*', IGNORE_MISSING);
    if ($request) {
        if ($action === 'delete') {
            if (!$confirm) {
                echo $OUTPUT->header();
                $deleteurl = new moodle_url('/local/spacechildpages/enrol_requests.php', [
                    'action' => 'delete',
                    'id' => $id,
                    'confirm' => 1,
                    'sesskey' => sesskey(),
                ]);
                $cancelurl = new moodle_url('/local/spacechildpages/enrol_requests.php');
                echo $OUTPUT->confirm(
                    get_string('enrolrequest:confirmdelete', 'local_spacechildpages', format_string($request->fullname)),
                    $deleteurl,
                    $cancelurl
                );
                echo $OUTPUT->footer();
                exit;
            }

            if (confirm_sesskey()) {
                $DB->delete_records('local_spacechildpages_enrolreq', ['id' => $id]);
                redirect(
                    new moodle_url('/local/spacechildpages/enrol_requests.php'),
                    get_string('enrolrequest:deleted', 'local_spacechildpages'),
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            }
        }

        if (($action === 'approve' || $action === 'reject') && confirm_sesskey()) {
            $message = get_string('enrolrequest:updated', 'local_spacechildpages');
            $level = \core\output\notification::NOTIFY_SUCCESS;

            if ($action === 'approve') {
                $enrolerror = '';
                $enrolmessage = '';

                if (!empty($request->courseid) && !empty($request->userid)) {
                    $course = $DB->get_record('course', ['id' => $request->courseid], 'id,fullname', IGNORE_MISSING);
                    $user = $DB->get_record('user', ['id' => $request->userid, 'deleted' => 0], 'id', IGNORE_MISSING);

                    if ($course && $user) {
                        $context = context_course::instance($course->id);
                        if (!is_enrolled($context, $user->id, '', true)) {
                            $instances = enrol_get_instances($course->id, false);
                            $manualinstance = null;
                            foreach ($instances as $instance) {
                                if ($instance->enrol === 'manual' && (int)$instance->status === ENROL_INSTANCE_ENABLED) {
                                    $manualinstance = $instance;
                                    break;
                                }
                            }

                            $plugin = enrol_get_plugin('manual');
                            if (empty($plugin)) {
                                $enrolerror = get_string('enrolrequest:manual_missing', 'local_spacechildpages');
                            } else if (empty($manualinstance)) {
                                $enrolerror = get_string('enrolrequest:manual_instance_missing', 'local_spacechildpages');
                            } else {
                                $roleid = (int)$manualinstance->roleid;
                                if (!$roleid) {
                                    $roleid = (int)get_config('enrol_manual', 'roleid');
                                }
                                if (!$roleid) {
                                    $roles = get_archetype_roles('student');
                                    if (!empty($roles)) {
                                        $role = reset($roles);
                                        $roleid = (int)$role->id;
                                    }
                                }

                                $plugin->enrol_user($manualinstance, $user->id, $roleid ?: null, time());
                                $enrolmessage = get_string('enrolrequest:approved_enrolled', 'local_spacechildpages');
                            }
                        } else {
                            $enrolmessage = get_string('enrolrequest:approved_already', 'local_spacechildpages');
                        }
                    } else {
                        $enrolmessage = get_string('enrolrequest:approved_noenrol', 'local_spacechildpages');
                    }
                } else {
                    $enrolmessage = get_string('enrolrequest:approved_noenrol', 'local_spacechildpages');
                }

                if ($enrolerror !== '') {
                    redirect(
                        new moodle_url('/local/spacechildpages/enrol_requests.php'),
                        $enrolerror,
                        null,
                        \core\output\notification::NOTIFY_ERROR
                    );
                }

                $request->status = 'approved';
                $request->timemodified = time();
                $DB->update_record('local_spacechildpages_enrolreq', $request);
                $message = $enrolmessage !== '' ? $enrolmessage : $message;
            } else {
                $request->status = 'rejected';
                $request->timemodified = time();
                $DB->update_record('local_spacechildpages_enrolreq', $request);
            }

            redirect(
                new moodle_url('/local/spacechildpages/enrol_requests.php'),
                $message,
                null,
                $level
            );
        }
    }
}

$PAGE->set_title(get_string('enrolrequests', 'local_spacechildpages'));
$PAGE->set_heading(get_string('enrolrequests', 'local_spacechildpages'));

echo $OUTPUT->header();

$records = $DB->get_records('local_spacechildpages_enrolreq', null, 'timecreated DESC');

if (empty($records)) {
    echo $OUTPUT->notification(get_string('enrolrequest:norequests', 'local_spacechildpages'), 'info');
    echo $OUTPUT->footer();
    exit;
}

$courseids = [];
foreach ($records as $record) {
    if (!empty($record->courseid)) {
        $courseids[] = (int)$record->courseid;
    }
}
$courseids = array_values(array_unique($courseids));
$courses = [];
if (!empty($courseids)) {
    $courses = $DB->get_records_list('course', 'id', $courseids, '', 'id,fullname');
}

$table = new html_table();
$table->head = [
    get_string('enrolrequest:date', 'local_spacechildpages'),
    get_string('enrolrequest:course_col', 'local_spacechildpages'),
    get_string('enrolrequest:fullname_col', 'local_spacechildpages'),
    get_string('enrolrequest:email_col', 'local_spacechildpages'),
    get_string('enrolrequest:organisation_col', 'local_spacechildpages'),
    get_string('enrolrequest:phone_col', 'local_spacechildpages'),
    get_string('enrolrequest:position_col', 'local_spacechildpages'),
    get_string('enrolrequest:message_col', 'local_spacechildpages'),
    get_string('enrolrequest:status_col', 'local_spacechildpages'),
    get_string('actions', 'local_spacechildpages'),
];

foreach ($records as $record) {
    $coursename = '-';
    if (!empty($record->courseid) && isset($courses[$record->courseid])) {
        $coursename = format_string($courses[$record->courseid]->fullname);
    }

    $statuskey = 'enrolrequest:status_' . $record->status;
    $status = get_string($statuskey, 'local_spacechildpages');

    $approveurl = new moodle_url('/local/spacechildpages/enrol_requests.php', [
        'action' => 'approve',
        'id' => $record->id,
        'sesskey' => sesskey(),
    ]);
    $rejecturl = new moodle_url('/local/spacechildpages/enrol_requests.php', [
        'action' => 'reject',
        'id' => $record->id,
        'sesskey' => sesskey(),
    ]);
    $deleteurl = new moodle_url('/local/spacechildpages/enrol_requests.php', [
        'action' => 'delete',
        'id' => $record->id,
        'sesskey' => sesskey(),
    ]);

    $actions = html_writer::link($approveurl, get_string('enrolrequest:approve', 'local_spacechildpages'))
        . ' | ' . html_writer::link($rejecturl, get_string('enrolrequest:reject', 'local_spacechildpages'))
        . ' | ' . html_writer::link($deleteurl, get_string('enrolrequest:delete', 'local_spacechildpages'));

    $table->data[] = [
        userdate((int)$record->timecreated),
        $coursename,
        format_string($record->fullname),
        s($record->email),
        format_string($record->organisation),
        s($record->phone),
        s($record->position),
        s($record->message),
        $status,
        $actions,
    ];
}

echo html_writer::table($table);
echo $OUTPUT->footer();
