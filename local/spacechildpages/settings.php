<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_spacechildpages_mcategories',
        get_string('marketingcategories', 'local_spacechildpages'),
        new moodle_url('/local/spacechildpages/marketing_categories.php')
    ));
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_spacechildpages_mcourses',
        get_string('marketingcourses', 'local_spacechildpages'),
        new moodle_url('/local/spacechildpages/marketing_courses.php')
    ));
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_spacechildpages_enrolrequests',
        get_string('enrolrequests', 'local_spacechildpages'),
        new moodle_url('/local/spacechildpages/enrol_requests.php')
    ));
}
