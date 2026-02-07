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
$string['categoryimage'] = 'Image';
$string['categoryimageurl'] = 'Image URL (optional)';
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
$string['enrolrequest:existing_question'] = 'Are you already enrolled in a course?';
$string['enrolrequest:existing_yes'] = 'Yes, I am already enrolled';
$string['enrolrequest:existing_no'] = 'No, first enrolment';
$string['enrolrequest:existing_note'] = 'We will use your profile information for this request.';
$string['enrolrequest:existing_email_notfound'] = 'Email not found. Please choose "No, first enrolment" to complete the full form.';
$string['enrolrequest:existing_email_duplicate'] = 'Multiple accounts use this email. Please contact support.';
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
$string['enrolrequest:norequests_filtered'] = 'No enrolment requests match your filters.';
$string['enrolrequest:missingtable'] = 'Enrollment requests table is missing. Run the plugin upgrade.';
$string['enrolrequest:approved_enrolled'] = 'Request approved and user enrolled.';
$string['enrolrequest:approved_already'] = 'Request approved. User already enrolled.';
$string['enrolrequest:approved_noenrol'] = 'Request approved. No enrolment done.';
$string['enrolrequest:approved_subject'] = 'Your enrolment request on {$a} has been approved';
$string['enrolrequest:approved_body'] = 'Hello {$a->fullname},' . "\n\n"
    . 'Your enrolment request has been approved.' . "\n\n"
    . 'Course: {$a->course}' . "\n"
    . 'Course access: {$a->courseurl}' . "\n\n"
    . 'Login details:' . "\n"
    . 'Login URL: {$a->loginurl}' . "\n"
    . 'Username: {$a->username}' . "\n"
    . 'Email: {$a->email}' . "\n\n"
    . 'Password: If you already have a password, use it. If not, check your email for the password setup message.' . "\n"
    . 'Forgot it? {$a->forgoturl}' . "\n\n"
    . 'Thanks,' . "\n"
    . '{$a->sitename}' . "\n";
$string['enrolrequest:user_notfound'] = 'No user account found for this email: {$a}';
$string['enrolrequest:manual_missing'] = 'Manual enrolment plugin is disabled.';
$string['enrolrequest:manual_instance_missing'] = 'No manual enrolment instance is enabled for this course.';
$string['enrolrequest:filter_status'] = 'Status';
$string['enrolrequest:filter_status_all'] = 'All statuses';
$string['enrolrequest:filter_course'] = 'Course';
$string['enrolrequest:filter_course_all'] = 'All courses';
$string['enrolrequest:filter_search'] = 'Search';
$string['enrolrequest:filter_search_placeholder'] = 'Name or email';
$string['enrolrequest:filter_apply'] = 'Apply filters';
$string['enrolrequest:filter_reset'] = 'Reset';
$string['enrolrequest:progress_link'] = 'View progress';
$string['field_fullname'] = 'Full name';
$string['field_email'] = 'Email';
$string['field_course'] = 'Course';
$string['field_course_select'] = 'Select a course';
$string['field_course_required'] = 'Please select a course.';
$string['field_phone'] = 'Phone';
$string['field_organisation'] = 'Organisation';
$string['field_position'] = 'Position';
$string['field_message'] = 'Message';
$string['field_phone_invalid'] = 'Invalid phone number';
$string['course_detail_category'] = 'Category';
$string['course_detail_teachers'] = 'Teachers';
$string['course_detail_start'] = 'Start';
$string['course_detail_end'] = 'End';
$string['course_detail_duration'] = 'Duration';
$string['course_detail_activities'] = 'Activities';
$string['progress:dashboard'] = 'Progress dashboard';
$string['progress:dashboard_title'] = 'Learner progress and completions';
$string['progress:filter_course'] = 'Course';
$string['progress:filter_course_all'] = 'All courses';
$string['progress:filter_userid'] = 'User ID';
$string['progress:filter_email'] = 'Email';
$string['progress:filter_email_placeholder'] = 'user@example.com';
$string['progress:filter_apply'] = 'Apply filters';
$string['progress:filter_reset'] = 'Reset';
$string['progress:email_invalid'] = 'Please enter a valid email address.';
$string['progress:email_notfound'] = 'No account found for this email address.';
$string['progress:email_duplicate'] = 'Multiple accounts use this email. Please search by user ID.';
$string['progress:user_notfound'] = 'User not found.';
$string['progress:missingtable'] = 'Progress tables are missing. Run the plugin upgrade.';
$string['progress:overview_title'] = 'Course completion tracking';
$string['progress:overview_select_course'] = 'Select a course to display learners and their progress.';
$string['progress:overview_empty'] = 'No learners found for this course.';
$string['progress:completions_title'] = 'Course completions';
$string['progress:progress_title'] = 'Progress milestones';
$string['progress:completions_empty'] = 'No completions found.';
$string['progress:progress_empty'] = 'No progress milestones found.';
$string['progress:col_index'] = 'No.';
$string['progress:col_date'] = 'Date';
$string['progress:col_course'] = 'Course';
$string['progress:col_user'] = 'Learner';
$string['progress:col_email'] = 'Email';
$string['progress:col_completion'] = 'Completion %';
$string['progress:col_grade'] = 'Grade';
$string['progress:col_notified'] = 'Notified';
$string['progress:col_milestone'] = 'Milestone';
$string['progress:view_report'] = 'View report';
