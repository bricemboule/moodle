<?php
namespace local_spacechildpages\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

class marketing_course_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'title', get_string('coursetitle', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required', null, 'client');

        $mform->addElement('textarea', 'summary', get_string('coursedescription', 'local_spacechildpages'), [
            'rows' => 3,
            'cols' => 60,
        ]);
        $mform->setType('summary', PARAM_TEXT);
        $mform->addRule('summary', null, 'required', null, 'client');

        $mform->addElement('text', 'imageurl', get_string('courseimage', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('imageurl', PARAM_URL);
        $mform->addRule('imageurl', null, 'required', null, 'client');

        $mform->addElement('text', 'sortorder', get_string('sortorder', 'local_spacechildpages'), ['size' => 6]);
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 0);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
