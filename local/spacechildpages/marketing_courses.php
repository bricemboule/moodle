<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_spacechildpages_mcourses');

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$deleteid = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if ($deleteid) {
    $course = $DB->get_record('local_spacechildpages_mcourses', ['id' => $deleteid], '*', IGNORE_MISSING);
    if ($course && !$confirm) {
        echo $OUTPUT->header();
        $deleteurl = new moodle_url('/local/spacechildpages/marketing_courses.php', [
            'delete' => $deleteid,
            'confirm' => 1,
            'sesskey' => sesskey(),
        ]);
        $cancelurl = new moodle_url('/local/spacechildpages/marketing_courses.php');
        echo $OUTPUT->confirm(
            get_string('confirmdeletecourse', 'local_spacechildpages', format_string($course->title)),
            $deleteurl,
            $cancelurl
        );
        echo $OUTPUT->footer();
        exit;
    }

    if ($course && $confirm && confirm_sesskey()) {
        $DB->delete_records('local_spacechildpages_mcourses', ['id' => $deleteid]);
        redirect(
            new moodle_url('/local/spacechildpages/marketing_courses.php'),
            get_string('coursedeleted', 'local_spacechildpages'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    }
}

$PAGE->set_title(get_string('marketingcourses', 'local_spacechildpages'));
$PAGE->set_heading(get_string('marketingcourses', 'local_spacechildpages'));

echo $OUTPUT->header();

$addurl = new moodle_url('/local/spacechildpages/marketing_course_edit.php');
echo $OUTPUT->single_button($addurl, get_string('addcourse', 'local_spacechildpages'), 'get');

$records = $DB->get_records('local_spacechildpages_mcourses', null, 'sortorder ASC, title ASC');

$table = new html_table();
$table->head = [
    get_string('coursetitle', 'local_spacechildpages'),
    get_string('coursedescription', 'local_spacechildpages'),
    get_string('courseimage', 'local_spacechildpages'),
    get_string('sortorder', 'local_spacechildpages'),
    get_string('actions', 'local_spacechildpages'),
];

foreach ($records as $record) {
    $image = '-';
    if (!empty($record->imageurl)) {
        $image = html_writer::empty_tag('img', [
            'src' => $record->imageurl,
            'alt' => s($record->title),
            'style' => 'height:40px;width:auto;',
        ]);
    }

    $editurl = new moodle_url('/local/spacechildpages/marketing_course_edit.php', ['id' => $record->id]);
    $deleteurl = new moodle_url('/local/spacechildpages/marketing_courses.php', [
        'delete' => $record->id,
        'sesskey' => sesskey(),
    ]);

    $actions = html_writer::link($editurl, get_string('editcourse', 'local_spacechildpages'))
        . ' | ' . html_writer::link($deleteurl, get_string('deletecourse', 'local_spacechildpages'));

    $table->data[] = [
        format_string($record->title),
        format_string($record->summary),
        $image,
        (int)$record->sortorder,
        $actions,
    ];
}

if (empty($table->data)) {
    echo $OUTPUT->notification(get_string('nocourses', 'local_spacechildpages'), 'info');
} else {
    echo html_writer::table($table);
}

echo $OUTPUT->footer();
