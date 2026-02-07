<?php
require_once('../../config.php');
require_once($CFG->libdir . '/modinfolib.php');


$courseid = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
if ((int)$course->id === SITEID) {
    throw new moodle_exception('invalidcourseid', 'error');
}

$context = context_course::instance($course->id);
if (!$course->visible && !has_capability('moodle/course:viewhiddencourses', $context)) {
    throw new moodle_exception('coursehidden', 'error');
}

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/spacechildpages/course_detail.php', ['courseid' => $course->id]));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->requires->css(new moodle_url('/theme/spacechild/style/marketing.css'));
$PAGE->requires->css(new moodle_url('/theme/spacechild/style/navigation-coursera.css'));
$PAGE->requires->css(new moodle_url('/local/spacechildpages/style/course_detail.css'));
$PAGE->requires->js(new moodle_url('/theme/spacechild/javascript/marketing.js'));

$summary = '';
if (!empty($course->summary)) {
    $summary = format_text($course->summary, $course->summaryformat, ['context' => $context, 'noclean' => true]);
}

$categoryname = '-';
try {
    $category = \core_course_category::get($course->category, IGNORE_MISSING);
    if ($category) {
        $categoryname = format_string($category->get_formatted_name());
    }
} catch (Exception $e) {
    $categoryname = '-';
}

$teacherroles = array_merge(get_archetype_roles('editingteacher'), get_archetype_roles('teacher'));
$roleids = [];
foreach ($teacherroles as $role) {
    $roleids[] = (int)$role->id;
}
$roleids = array_values(array_unique($roleids));
$teachers = [];
if (!empty($roleids)) {
    foreach ($roleids as $roleid) {
        $roleusers = get_role_users(
            $roleid,
            $context,
            false,
            'u.id, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename',
            'u.lastname ASC'
        );
        foreach ($roleusers as $userid => $roleuser) {
            $teachers[$userid] = $roleuser;
        }
    }
}
$teachernames = [];
if (!empty($teachers)) {
    foreach ($teachers as $teacher) {
        $teachernames[] = fullname($teacher);
    }
}

$startdate = $course->startdate ? userdate($course->startdate) : '-';
$enddate = !empty($course->enddate) ? userdate($course->enddate) : '-';
$duration = '-';
if (!empty($course->startdate) && !empty($course->enddate) && $course->enddate > $course->startdate) {
    $duration = format_time($course->enddate - $course->startdate);
}

$activitycount = 0;
try {
    $modinfo = get_fast_modinfo($course);
    foreach ($modinfo->get_cms() as $cm) {
        if (!$cm->uservisible) {
            continue;
        }
        if ($cm->modname === 'label') {
            continue;
        }
        $activitycount++;
    }
} catch (Exception $e) {
    $activitycount = 0;
}

$courseimage = null;
$courseelement = new core_course_list_element($course);
foreach ($courseelement->get_course_overviewfiles() as $file) {
    if ($file->is_valid_image()) {
        $courseimage = moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            null,
            $file->get_filepath(),
            $file->get_filename()
        )->out(false);
        break;
    }
}

$enrolurl = new moodle_url('/local/spacechildpages/enrol_request.php', ['courseid' => $course->id]);
$peopleurl = new moodle_url('/local/spacechildpages/people.php');
$universitiesurl = new moodle_url('/local/spacechildpages/universities.php');
$governmentsurl = new moodle_url('/local/spacechildpages/governments.php');
$loginurl = new moodle_url('/login/index.php');
$signupurl = new moodle_url('/local/spacechildpages/enrol_request.php');
$searchurl = new moodle_url('/course/search.php');
$exploreurl = new moodle_url('/course/index.php');
$supporturl = new moodle_url('/user/contactsitesupport.php');

echo $OUTPUT->header();

echo html_writer::start_tag('div', ['class' => 'sc-landing sc-landing--full sc-course-detail']);

// Top bar.
echo html_writer::start_tag('div', ['class' => 'sc-topbar']);
echo html_writer::start_tag('div', ['class' => 'sc-container sc-topbar-inner']);
echo html_writer::link($peopleurl, 'Pour les personnes');
echo html_writer::link($universitiesurl, 'Pour les universités');
echo html_writer::link($governmentsurl, 'Pour les gouvernements');
echo html_writer::end_tag('div');
echo html_writer::end_tag('div');

// Navigation.
echo html_writer::start_tag('header', ['class' => 'sc-nav']);
echo html_writer::start_tag('div', ['class' => 'sc-container sc-nav-inner']);
echo html_writer::link(
    new moodle_url('/'),
    html_writer::empty_tag('img', [
        'class' => 'sc-logo-image',
        'src' => $CFG->wwwroot . '/theme/spacechild/images/LOGO.png',
        'alt' => format_string($SITE->shortname ?: $SITE->fullname),
    ]),
    ['class' => 'sc-logo']
);
echo html_writer::link($exploreurl, 'Explorer', ['class' => 'sc-nav-link']);
echo html_writer::start_tag('form', [
    'class' => 'sc-nav-search',
    'method' => 'get',
    'action' => $searchurl->out(false),
    'role' => 'search',
]);
echo html_writer::tag('label', 'Que souhaitez-vous apprendre ?', [
    'class' => 'sc-visually-hidden',
    'for' => 'sc-nav-search',
]);
echo html_writer::empty_tag('input', [
    'id' => 'sc-nav-search',
    'type' => 'text',
    'name' => 'search',
    'placeholder' => 'Que souhaitez-vous apprendre ?',
    'aria-label' => 'Que souhaitez-vous apprendre ?',
]);
echo html_writer::tag(
    'button',
    '<svg class="sc-search-btn__icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.5"/><path d="M12.5 12.5L16.5 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
    ['class' => 'sc-search-btn', 'type' => 'submit', 'aria-label' => 'Rechercher']
);
echo html_writer::end_tag('form');
echo html_writer::start_tag('nav', ['class' => 'sc-nav-actions', 'aria-label' => 'Actions utilisateur']);
echo html_writer::link($loginurl, 'Connexion', ['class' => 'sc-link', 'data-login-modal' => true, 'data-modal-title' => 'Connexion']);
echo html_writer::link($signupurl, "S'inscrire gratuitement", ['class' => 'sc-btn sc-btn--signup', 'data-signup-modal' => true, 'data-modal-title' => 'Inscription']);
echo html_writer::end_tag('nav');
echo html_writer::end_tag('div');
echo html_writer::end_tag('header');

echo html_writer::start_tag('section', ['class' => 'sc-section']);
echo html_writer::start_tag('div', ['class' => 'sc-container']);

echo html_writer::start_tag('div', ['class' => 'sc-course-detail__grid']);

echo html_writer::start_tag('div', ['class' => 'sc-course-detail__content']);
echo html_writer::tag('span', 'Cours', ['class' => 'sc-card-tag']);
echo html_writer::tag('h1', format_string($course->fullname), ['class' => 'sc-course-detail__title']);
if ($summary !== '') {
    echo html_writer::tag('div', $summary, ['class' => 'sc-course-detail__summary']);
}

echo html_writer::start_tag('div', ['class' => 'sc-course-detail__meta']);
echo html_writer::tag('div', get_string('course_detail_category', 'local_spacechildpages') . ': ' . $categoryname, ['class' => 'sc-course-detail__meta-row']);
echo html_writer::tag('div', get_string('course_detail_teachers', 'local_spacechildpages') . ': ' . (!empty($teachernames) ? implode(', ', $teachernames) : '-'), ['class' => 'sc-course-detail__meta-row']);
echo html_writer::tag('div', get_string('course_detail_start', 'local_spacechildpages') . ': ' . $startdate, ['class' => 'sc-course-detail__meta-row']);
echo html_writer::tag('div', get_string('course_detail_end', 'local_spacechildpages') . ': ' . $enddate, ['class' => 'sc-course-detail__meta-row']);
echo html_writer::tag('div', get_string('course_detail_duration', 'local_spacechildpages') . ': ' . $duration, ['class' => 'sc-course-detail__meta-row']);
echo html_writer::tag('div', get_string('course_detail_activities', 'local_spacechildpages') . ': ' . ($activitycount ?: '-'), ['class' => 'sc-course-detail__meta-row']);
echo html_writer::end_tag('div');

echo html_writer::start_tag('div', ['class' => 'sc-course-detail__actions']);
echo html_writer::link($enrolurl, 'Demander l\'inscription', ['class' => 'sc-btn sc-btn--primary']);
echo html_writer::link(new moodle_url('/course/index.php'), 'Voir le catalogue', ['class' => 'sc-btn sc-btn--ghost']);
echo html_writer::end_tag('div');

echo html_writer::end_tag('div');

if (!empty($courseimage)) {
    echo html_writer::start_tag('div', ['class' => 'sc-course-detail__media']);
    echo html_writer::empty_tag('img', ['src' => $courseimage, 'alt' => format_string($course->fullname), 'loading' => 'lazy']);
    echo html_writer::end_tag('div');
}

echo html_writer::end_tag('div');

echo html_writer::end_tag('div');
echo html_writer::end_tag('section');

echo $OUTPUT->render_from_template('theme_spacechild/spacechild/landing_footer', [
    'wwwroot' => $CFG->wwwroot,
    'peopleurl' => $peopleurl->out(false),
    'universitiesurl' => $universitiesurl->out(false),
    'governmentsurl' => $governmentsurl->out(false),
    'loginurl' => $loginurl->out(false),
    'signupurl' => $signupurl->out(false),
    'supporturl' => $supporturl->out(false),
    'currentyear' => date('Y'),
]);

echo html_writer::end_tag('div');

echo $OUTPUT->footer();
