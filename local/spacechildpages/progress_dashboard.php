<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_spacechildpages_progress');

$PAGE->add_body_class('sc-progress-dashboard');
$PAGE->requires->css(new moodle_url('/local/spacechildpages/style/progress_dashboard.css'));

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$PAGE->requires->js_init_code(<<<'JS'
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('.sc-progress-dashboard__filters');
  if (!form) {
    return;
  }

  const submit = () => {
    if (typeof form.requestSubmit === 'function') {
      form.requestSubmit();
    } else {
      form.submit();
    }
  };

  const courseSelect = form.querySelector('select[name="courseid"]');
  if (courseSelect) {
    courseSelect.addEventListener('change', submit);
  }

  const userInput = form.querySelector('input[name="userid"]');
  if (userInput) {
    userInput.addEventListener('change', submit);
  }

  const emailInput = form.querySelector('input[name="email"]');
  if (emailInput) {
    let emailTimer = null;
    const queueSubmit = () => {
      window.clearTimeout(emailTimer);
      emailTimer = window.setTimeout(submit, 500);
    };
    emailInput.addEventListener('input', queueSubmit);
    emailInput.addEventListener('change', queueSubmit);
  }
});
JS
);

$dbman = $DB->get_manager();
if (!$dbman->table_exists('local_spacechildpages_completions') || !$dbman->table_exists('local_spacechildpages_progress')) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('progress:missingtable', 'local_spacechildpages'), 'error');
    echo $OUTPUT->footer();
    exit;
}

$courseid = optional_param('courseid', 0, PARAM_INT);
$userid = optional_param('userid', 0, PARAM_INT);
$email = trim(optional_param('email', '', PARAM_RAW_TRIMMED));

$messages = [];
$resolveduser = null;

if (!empty($userid)) {
    $resolveduser = \core_user::get_user($userid, 'id,firstname,lastname,email,deleted', IGNORE_MISSING);
    if (!$resolveduser || !empty($resolveduser->deleted)) {
        $messages[] = get_string('progress:user_notfound', 'local_spacechildpages');
        $userid = 0;
        $resolveduser = null;
    } else if ($email === '') {
        $email = $resolveduser->email;
    }
}

if (empty($userid) && $email !== '') {
    $cleanemail = clean_param($email, PARAM_EMAIL);
    if ($cleanemail === '') {
        $messages[] = get_string('progress:email_invalid', 'local_spacechildpages');
    } else {
        $cleanemail = core_text::strtolower($cleanemail);
        $sql = "SELECT id, firstname, lastname, email, deleted
                  FROM {user}
                 WHERE LOWER(email) = ?
                   AND mnethostid = ?";
        $users = $DB->get_records_sql($sql, [$cleanemail, $CFG->mnet_localhost_id], 0, 2);
        if (count($users) === 1) {
            $candidate = reset($users);
            if (!empty($candidate->deleted)) {
                $messages[] = get_string('progress:user_notfound', 'local_spacechildpages');
            } else {
                $resolveduser = $candidate;
                $userid = (int)$candidate->id;
                $email = $candidate->email;
            }
        } else if (count($users) > 1) {
            $messages[] = get_string('progress:email_duplicate', 'local_spacechildpages');
        } else {
            $messages[] = get_string('progress:email_notfound', 'local_spacechildpages');
        }
    }
}

$PAGE->set_title(get_string('progress:dashboard', 'local_spacechildpages'));
$PAGE->set_heading(get_string('progress:dashboard', 'local_spacechildpages'));

echo $OUTPUT->header();

echo html_writer::tag('h2', get_string('progress:dashboard_title', 'local_spacechildpages'), [
    'class' => 'sc-progress-dashboard__title',
]);

$courselist = $DB->get_records_sql(
    "SELECT id, fullname
       FROM {course}
      WHERE id <> :siteid
   ORDER BY fullname",
    ['siteid' => SITEID]
);

$courseoptions = [
    0 => get_string('progress:filter_course_all', 'local_spacechildpages'),
];
foreach ($courselist as $courseitem) {
    $courseoptions[$courseitem->id] = format_string($courseitem->fullname);
}

echo html_writer::start_tag('form', [
    'class' => 'sc-progress-dashboard__filters',
    'method' => 'get',
    'action' => (new moodle_url('/local/spacechildpages/progress_dashboard.php'))->out(false),
]);

echo html_writer::start_tag('div', ['class' => 'sc-progress-dashboard__filters-row']);

echo html_writer::start_tag('div', ['class' => 'sc-progress-dashboard__filter']);
echo html_writer::tag('label', get_string('progress:filter_course', 'local_spacechildpages'), [
    'class' => 'sc-progress-dashboard__filter-label',
]);
echo html_writer::select(
    $courseoptions,
    'courseid',
    $courseid,
    false,
    ['class' => 'sc-progress-dashboard__filter-select']
);
echo html_writer::end_tag('div');

echo html_writer::start_tag('div', ['class' => 'sc-progress-dashboard__filter']);
echo html_writer::tag('label', get_string('progress:filter_userid', 'local_spacechildpages'), [
    'class' => 'sc-progress-dashboard__filter-label',
]);
echo html_writer::empty_tag('input', [
    'type' => 'number',
    'name' => 'userid',
    'value' => $userid ?: '',
    'min' => 0,
    'class' => 'sc-progress-dashboard__filter-input',
]);
echo html_writer::end_tag('div');

echo html_writer::start_tag('div', ['class' => 'sc-progress-dashboard__filter']);
echo html_writer::tag('label', get_string('progress:filter_email', 'local_spacechildpages'), [
    'class' => 'sc-progress-dashboard__filter-label',
]);
echo html_writer::empty_tag('input', [
    'type' => 'text',
    'name' => 'email',
    'value' => $email,
    'placeholder' => get_string('progress:filter_email_placeholder', 'local_spacechildpages'),
    'class' => 'sc-progress-dashboard__filter-input',
]);
echo html_writer::end_tag('div');

echo html_writer::start_tag('div', ['class' => 'sc-progress-dashboard__filter-actions']);
echo html_writer::empty_tag('input', [
    'type' => 'submit',
    'value' => get_string('progress:filter_apply', 'local_spacechildpages'),
    'class' => 'sc-progress-dashboard__filter-submit',
]);
echo html_writer::link(
    new moodle_url('/local/spacechildpages/progress_dashboard.php'),
    get_string('progress:filter_reset', 'local_spacechildpages'),
    ['class' => 'sc-progress-dashboard__filter-reset']
);
echo html_writer::end_tag('div');

echo html_writer::end_tag('div');
echo html_writer::end_tag('form');

foreach ($messages as $message) {
    echo $OUTPUT->notification($message, 'error');
}

echo html_writer::tag('h3', get_string('progress:overview_title', 'local_spacechildpages'), [
    'class' => 'sc-progress-dashboard__section-title',
]);

$studentroles = get_archetype_roles('student');
$roleids = [];
foreach ($studentroles as $role) {
    $roleids[] = (int)$role->id;
}
$roleids = array_values(array_unique($roleids));

$overviewrows = [];
if (!empty($roleids)) {
    list($rolesql, $roleparams) = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED, 'role');
    $conditions = [
        'ctx.contextlevel = :contextlevel',
        'c.id <> :siteid',
        'u.deleted = 0',
        "ra.roleid {$rolesql}",
    ];
    $params = array_merge($roleparams, [
        'contextlevel' => CONTEXT_COURSE,
        'siteid' => SITEID,
    ]);
    if (!empty($courseid)) {
        $conditions[] = 'c.id = :courseid';
        $params['courseid'] = $courseid;
    }
    if (!empty($userid)) {
        $conditions[] = 'u.id = :userid';
        $params['userid'] = $userid;
    }

    $wheresql = 'WHERE ' . implode(' AND ', $conditions);
    $recordset = $DB->get_recordset_sql(
        "SELECT DISTINCT u.id AS userid,
                u.firstname,
                u.lastname,
                u.firstnamephonetic,
                u.lastnamephonetic,
                u.middlename,
                u.alternatename,
                c.id AS courseid,
                c.fullname AS coursename
           FROM {role_assignments} ra
           JOIN {context} ctx ON ctx.id = ra.contextid
           JOIN {course} c ON c.id = ctx.instanceid
           JOIN {user} u ON u.id = ra.userid
         {$wheresql}
       ORDER BY c.fullname ASC, u.lastname ASC, u.firstname ASC",
        $params
    );
    foreach ($recordset as $record) {
        $overviewrows[] = $record;
    }
    $recordset->close();
}

if (empty($overviewrows)) {
    echo $OUTPUT->notification(get_string('progress:overview_empty', 'local_spacechildpages'), 'info');
} else {
    $courseids = [];
    $userids = [];
    foreach ($overviewrows as $row) {
        $courseids[] = (int)$row->courseid;
        $userids[] = (int)$row->userid;
    }
    $courseids = array_values(array_unique($courseids));
    $userids = array_values(array_unique($userids));

    $coursemap = [];
    if (!empty($courseids)) {
        $coursemap = $DB->get_records_list('course', 'id', $courseids, '', '*');
    }

    $milestones = [];
    if (!empty($courseids) && !empty($userids)) {
        list($usersql, $userparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED, 'uid');
        list($coursesql, $courseparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'cid');
        $milestoneparams = array_merge($userparams, $courseparams);

        $milestonerecords = $DB->get_recordset_sql(
            "SELECT userid, courseid, MAX(milestone) AS milestone
               FROM {local_spacechildpages_progress}
              WHERE userid {$usersql}
                AND courseid {$coursesql}
           GROUP BY userid, courseid",
            $milestoneparams
        );
        foreach ($milestonerecords as $record) {
            $key = (int)$record->userid . '-' . (int)$record->courseid;
            $milestones[$key] = (int)$record->milestone;
        }
        $milestonerecords->close();
    }

    $grouped = [];
    foreach ($overviewrows as $row) {
        $userid = (int)$row->userid;
        if (!isset($grouped[$userid])) {
            $grouped[$userid] = [
                'user' => (object) [
                    'id' => $userid,
                    'firstname' => $row->firstname,
                    'lastname' => $row->lastname,
                    'firstnamephonetic' => $row->firstnamephonetic,
                    'lastnamephonetic' => $row->lastnamephonetic,
                    'middlename' => $row->middlename,
                    'alternatename' => $row->alternatename,
                ],
                'courses' => [],
                'completions' => [],
                'milestones' => [],
            ];
        }

        $course = $coursemap[$row->courseid] ?? null;
        $completion = null;
        if ($course) {
            try {
                $completion = \core_completion\progress::get_course_progress_percentage($course, $userid);
            } catch (Exception $e) {
                $completion = null;
            }
        }
        $completionlabel = $completion === null ? '-' : format_float($completion, 2) . '%';
        $milestonekey = $userid . '-' . (int)$row->courseid;
        $milestone = isset($milestones[$milestonekey]) ? $milestones[$milestonekey] . '%' : '-';

        $grouped[$userid]['courses'][] = $row->coursename;
        $grouped[$userid]['completions'][] = $completionlabel;
        $grouped[$userid]['milestones'][] = $milestone;
    }

    $table = new html_table();
    $table->head = [
        get_string('progress:col_index', 'local_spacechildpages'),
        get_string('progress:col_user', 'local_spacechildpages'),
        get_string('progress:col_course', 'local_spacechildpages'),
        get_string('progress:col_completion', 'local_spacechildpages'),
        get_string('progress:col_milestone', 'local_spacechildpages'),
    ];

    $index = 0;
    foreach ($grouped as $entry) {
        $index++;
        $rowcount = max(1, count($entry['courses']));

        $indexcell = new html_table_cell($index);
        $indexcell->rowspan = $rowcount;
        $namecell = new html_table_cell(format_string(fullname($entry['user'])));
        $namecell->rowspan = $rowcount;

        for ($i = 0; $i < $rowcount; $i++) {
            $course = $entry['courses'][$i] ?? '-';
            $completionlabel = $entry['completions'][$i] ?? '-';
            $milestonelabel = $entry['milestones'][$i] ?? '-';

            $rowattrs = ['class' => 'sc-progress-dashboard__row'];
            if ($i > 0) {
                $rowattrs['class'] .= ' sc-progress-dashboard__row--sub';
            }

            if ($i === 0) {
                $table->data[] = new html_table_row([
                    $indexcell,
                    $namecell,
                    s($course),
                    s($completionlabel),
                    s($milestonelabel),
                ], $rowattrs);
            } else {
                $table->data[] = new html_table_row([
                    s($course),
                    s($completionlabel),
                    s($milestonelabel),
                ], $rowattrs);
            }
        }
    }

    $table->attributes['class'] = trim(($table->attributes['class'] ?? '') . ' sc-progress-dashboard__table');
    echo html_writer::start_tag('div', ['class' => 'sc-progress-dashboard__table-wrap']);
    echo html_writer::table($table);
    echo html_writer::end_tag('div');
}

echo $OUTPUT->footer();
