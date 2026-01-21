<?php
namespace local_spacechildpages\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

class marketing_category_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('categoryname', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('text', 'meta', get_string('categorymeta', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('meta', PARAM_TEXT);

        $mform->addElement('text', 'imageurl', get_string('categoryimage', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('imageurl', PARAM_URL);

        $mform->addElement('text', 'linkurl', get_string('categoryurl', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('linkurl', PARAM_URL);
        $mform->addRule('linkurl', null, 'required', null, 'client');

        $mform->addElement('text', 'sortorder', get_string('sortorder', 'local_spacechildpages'), ['size' => 6]);
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 0);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
