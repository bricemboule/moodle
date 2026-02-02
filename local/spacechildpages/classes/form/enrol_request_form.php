<?php
namespace local_spacechildpages\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class enrol_request_form extends \moodleform {

    public function definition() {
        $mform = $this->_form;
        $customdata = $this->_customdata ?? [];

        $courseid = (int)($customdata['courseid'] ?? 0);
        $courses = $customdata['courses'] ?? [];
        $courseLocked = !empty($customdata['course_locked']);
        $defaults = $customdata['defaults'] ?? [];

        $this->set_display_vertical();
        $mform->setRequiredNote('');

        $mform->addElement('header', 'details', get_string('enrolrequest:details', 'local_spacechildpages'));

        if ($courseLocked) {
            $mform->addElement('hidden', 'courseid', $courseid);
            $mform->setType('courseid', PARAM_INT);
        } else {
            $options = [0 => get_string('field_course_select', 'local_spacechildpages')] + $courses;
            $mform->addElement(
                'select',
                'courseid',
                get_string('field_course', 'local_spacechildpages'),
                $options
            );
            $mform->setType('courseid', PARAM_INT);
            $mform->addRule('courseid', null, 'required', null, 'client');
        }

        $mform->addElement(
            'text',
            'fullname',
            get_string('field_fullname', 'local_spacechildpages'),
            ['placeholder' => get_string('field_fullname', 'local_spacechildpages')]
        );
        $mform->setType('fullname', PARAM_TEXT);
        $mform->addRule('fullname', null, 'required', null, 'client');

        $mform->addElement(
            'text',
            'email',
            get_string('field_email', 'local_spacechildpages'),
            ['placeholder' => get_string('field_email', 'local_spacechildpages')]
        );
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', null, 'required', null, 'client');
        $mform->addRule('email', null, 'email', null, 'client');

        $mform->addElement(
            'text',
            'phone',
            get_string('field_phone', 'local_spacechildpages'),
            ['placeholder' => get_string('field_phone', 'local_spacechildpages')]
        );
        $mform->setType('phone', PARAM_TEXT);

        $mform->addElement(
            'text',
            'organisation',
            get_string('field_organisation', 'local_spacechildpages'),
            ['placeholder' => get_string('field_organisation', 'local_spacechildpages')]
        );
        $mform->setType('organisation', PARAM_TEXT);
        $mform->addRule('organisation', null, 'required', null, 'client');

        $mform->addElement(
            'text',
            'position',
            get_string('field_position', 'local_spacechildpages'),
            ['placeholder' => get_string('field_position', 'local_spacechildpages')]
        );
        $mform->setType('position', PARAM_TEXT);

        $mform->addElement(
            'textarea',
            'message',
            get_string('field_message', 'local_spacechildpages'),
            [
                'rows' => 4,
                'cols' => 60,
                'placeholder' => get_string('field_message', 'local_spacechildpages'),
            ]
        );
        $mform->setType('message', PARAM_TEXT);

        foreach ($defaults as $key => $value) {
            if ($mform->elementExists($key)) {
                $mform->setDefault($key, $value);
            }
        }

        $this->add_action_buttons(true, get_string('enrolrequest:button', 'local_spacechildpages'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $customdata = $this->_customdata ?? [];
        $courseLocked = !empty($customdata['course_locked']);
        $courses = $customdata['courses'] ?? [];

        if (!empty($data['phone']) && strlen(trim($data['phone'])) < 6) {
            $errors['phone'] = get_string('field_phone_invalid', 'local_spacechildpages');
        }

        if (!empty($data['email']) && !validate_email($data['email'])) {
            $errors['email'] = get_string('invalidemail');
        }

        if (!$courseLocked) {
            $selected = (int)($data['courseid'] ?? 0);
            if (empty($selected) || !isset($courses[$selected])) {
                $errors['courseid'] = get_string('field_course_required', 'local_spacechildpages');
            }
        }

        return $errors;
    }
}
