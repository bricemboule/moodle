<?php
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Spacechild pages';
$string['marketingcategories'] = 'Marketing categories';
$string['marketingcourses'] = 'Marketing courses';
$string['addcategory'] = 'Add category';
$string['addcourse'] = 'Add course';
$string['editcategory'] = 'Edit category';
$string['editcourse'] = 'Edit course';
$string['deletecategory'] = 'Delete';
$string['deletecourse'] = 'Delete';
$string['confirmdeletecategory'] = 'Delete category "{$a}"?';
$string['confirmdeletecourse'] = 'Delete course "{$a}"?';
$string['categoryname'] = 'Name';
$string['categorymeta'] = 'Meta';
$string['categoryimage'] = 'Image URL';
$string['categoryurl'] = 'Link URL';
$string['coursetitle'] = 'Course name';
$string['coursedescription'] = 'Short description';
$string['courseimage'] = 'Image URL';
$string['sortorder'] = 'Sort order';
$string['actions'] = 'Actions';
$string['categorysaved'] = 'Category saved.';
$string['categorydeleted'] = 'Category deleted.';
$string['nocategories'] = 'No marketing categories yet.';
$string['coursesaved'] = 'Course saved.';
$string['coursedeleted'] = 'Course deleted.';
$string['nocourses'] = 'No marketing courses yet.';
$string['enrolrequests'] = 'Course enrolment requests';
$string['enrolrequest:title'] = 'Course enrolment request';
$string['enrolrequest:details'] = 'Your details';
$string['enrolrequest:intro'] = 'Please complete the form below. An administrator will review your request before enrolment.';
$string['enrolrequest:button'] = 'Send request';
$string['enrolrequest:sent'] = 'Your request has been sent.';
$string['enrolrequest:email_subject'] = 'New course enrolment request';
$string['enrolrequest:email_body'] = 'New enrolment request' . "\n\n"
    . 'Name: {$a->fullname}' . "\n"
    . 'Email: {$a->email}' . "\n"
    . 'Organisation: {$a->organisation}' . "\n"
    . 'Phone: {$a->phone}' . "\n"
    . 'Position: {$a->position}' . "\n"
    . 'Course: {$a->course}' . "\n"
    . 'Message: {$a->message}' . "\n\n"
    . 'Manage requests: {$a->url}' . "\n";
$string['enrolrequest:nocourse'] = 'No course selected';
$string['enrolrequest:course'] = 'Course: {$a}';
$string['enrolrequest:date'] = 'Date';
$string['enrolrequest:course_col'] = 'Course';
$string['enrolrequest:fullname_col'] = 'Full name';
$string['enrolrequest:email_col'] = 'Email';
$string['enrolrequest:organisation_col'] = 'Organisation';
$string['enrolrequest:phone_col'] = 'Phone';
$string['enrolrequest:position_col'] = 'Position';
$string['enrolrequest:message_col'] = 'Message';
$string['enrolrequest:status_col'] = 'Status';
$string['enrolrequest:status_pending'] = 'Pending';
$string['enrolrequest:status_approved'] = 'Approved';
$string['enrolrequest:status_rejected'] = 'Rejected';
$string['enrolrequest:approve'] = 'Approve';
$string['enrolrequest:reject'] = 'Reject';
$string['enrolrequest:delete'] = 'Delete';
$string['enrolrequest:confirmdelete'] = 'Delete request for "{$a}"?';
$string['enrolrequest:deleted'] = 'Request deleted.';
$string['enrolrequest:updated'] = 'Request updated.';
$string['enrolrequest:norequests'] = 'No enrolment requests yet.';
$string['enrolrequest:missingtable'] = 'Enrollment requests table is missing. Run the plugin upgrade.';
$string['enrolrequest:approved_enrolled'] = 'Request approved and user enrolled.';
$string['enrolrequest:approved_already'] = 'Request approved. User already enrolled.';
$string['enrolrequest:approved_noenrol'] = 'Request approved. No enrolment done.';
$string['enrolrequest:manual_missing'] = 'Manual enrolment plugin is disabled.';
$string['enrolrequest:manual_instance_missing'] = 'No manual enrolment instance is enabled for this course.';
$string['field_fullname'] = 'Full name';
$string['field_email'] = 'Email';
$string['field_phone'] = 'Phone';
$string['field_organisation'] = 'Organisation';
$string['field_position'] = 'Position';
$string['field_message'] = 'Message';
$string['field_phone_invalid'] = 'Invalid phone number';
