<?php
namespace local_spacechildpages\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/filelib.php');

class marketing_category_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $filemanageroptions = $this->_customdata['filemanageroptions'] ?? [
            'maxbytes' => 0,
            'maxfiles' => 1,
            'subdirs' => 0,
            'accepted_types' => ['image'],
        ];

        $mform->addElement('text', 'name', get_string('categoryname', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('text', 'meta', get_string('categorymeta', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('meta', PARAM_TEXT);

        $mform->addElement('filemanager', 'imagefile', get_string('categoryimage', 'local_spacechildpages'), null, $filemanageroptions);

        $mform->addElement('text', 'imageurl', get_string('categoryimageurl', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('imageurl', PARAM_URL);
        $mform->addRule('imageurl', null, 'url', null, 'client');

        $mform->addElement('text', 'linkurl', get_string('categoryurl', 'local_spacechildpages'), ['size' => 64]);
        $mform->setType('linkurl', PARAM_URL);
        $mform->addRule('linkurl', null, 'required', null, 'client');

        $mform->addElement('text', 'sortorder', get_string('sortorder', 'local_spacechildpages'), ['size' => 6]);
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 0);

        $this->add_action_buttons(true, get_string('savechanges'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $hasfile = false;

        if (!empty($data['imagefile'])) {
            $info = file_get_draft_area_info($data['imagefile']);
            $hasfile = !empty($info['filecount']);
        }

        if (!$hasfile && empty(trim((string)($data['imageurl'] ?? '')))) {
            $errors['imagefile'] = get_string('required');
        }

        return $errors;
    }
}
