<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_spacechildpages_mcategories');

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$deleteid = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if ($deleteid) {
    $category = $DB->get_record('local_spacechildpages_mcategories', ['id' => $deleteid], '*', IGNORE_MISSING);
    if ($category && !$confirm) {
        echo $OUTPUT->header();
        $deleteurl = new moodle_url('/local/spacechildpages/marketing_categories.php', [
            'delete' => $deleteid,
            'confirm' => 1,
            'sesskey' => sesskey(),
        ]);
        $cancelurl = new moodle_url('/local/spacechildpages/marketing_categories.php');
        echo $OUTPUT->confirm(
            get_string('confirmdeletecategory', 'local_spacechildpages', format_string($category->name)),
            $deleteurl,
            $cancelurl
        );
        echo $OUTPUT->footer();
        exit;
    }

    if ($category && $confirm && confirm_sesskey()) {
        $DB->delete_records('local_spacechildpages_mcategories', ['id' => $deleteid]);
        redirect(
            new moodle_url('/local/spacechildpages/marketing_categories.php'),
            get_string('categorydeleted', 'local_spacechildpages'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    }
}

$PAGE->set_title(get_string('marketingcategories', 'local_spacechildpages'));
$PAGE->set_heading(get_string('marketingcategories', 'local_spacechildpages'));

echo $OUTPUT->header();

$addurl = new moodle_url('/local/spacechildpages/marketing_category_edit.php');
echo $OUTPUT->single_button($addurl, get_string('addcategory', 'local_spacechildpages'), 'get');

$records = $DB->get_records('local_spacechildpages_mcategories', null, 'sortorder ASC, name ASC');

$table = new html_table();
$table->head = [
    get_string('categoryname', 'local_spacechildpages'),
    get_string('categorymeta', 'local_spacechildpages'),
    get_string('categoryimage', 'local_spacechildpages'),
    get_string('categoryurl', 'local_spacechildpages'),
    get_string('sortorder', 'local_spacechildpages'),
    get_string('actions', 'local_spacechildpages'),
];

foreach ($records as $record) {
    $image = '-';
    if (!empty($record->imageurl)) {
        $image = html_writer::empty_tag('img', [
            'src' => $record->imageurl,
            'alt' => s($record->name),
            'style' => 'height:40px;width:auto;',
        ]);
    }

    $editurl = new moodle_url('/local/spacechildpages/marketing_category_edit.php', ['id' => $record->id]);
    $deleteurl = new moodle_url('/local/spacechildpages/marketing_categories.php', [
        'delete' => $record->id,
        'sesskey' => sesskey(),
    ]);

    $actions = html_writer::link($editurl, get_string('editcategory', 'local_spacechildpages'))
        . ' | ' . html_writer::link($deleteurl, get_string('deletecategory', 'local_spacechildpages'));

    $table->data[] = [
        format_string($record->name),
        format_string($record->meta),
        $image,
        s($record->linkurl),
        (int)$record->sortorder,
        $actions,
    ];
}

if (empty($table->data)) {
    echo $OUTPUT->notification(get_string('nocategories', 'local_spacechildpages'), 'info');
} else {
    echo html_writer::table($table);
}

echo $OUTPUT->footer();
