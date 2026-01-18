<?php
namespace local_spacechildpages\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class business_contact_form extends \moodleform {

    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'fullname', 'Nom complet', ['size' => 50]);
        $mform->setType('fullname', PARAM_TEXT);
        $mform->addRule('fullname', 'Champ obligatoire', 'required', null, 'client');

        $mform->addElement('text', 'email', 'Email professionnel', ['size' => 50]);
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', 'Champ obligatoire', 'required', null, 'client');
        $mform->addRule('email', 'Email invalide', 'email', null, 'client');

        $mform->addElement('text', 'company', 'Entreprise / Organisation', ['size' => 50]);
        $mform->setType('company', PARAM_TEXT);
        $mform->addRule('company', 'Champ obligatoire', 'required', null, 'client');

        $mform->addElement('text', 'phone', 'Téléphone (optionnel)', ['size' => 30]);
        $mform->setType('phone', PARAM_TEXT);

        $sizes = [
            '1-10' => '1–10',
            '11-50' => '11–50',
            '51-200' => '51–200',
            '200+' => '200+',
        ];
        $mform->addElement('select', 'teamsize', 'Taille de l’équipe', $sizes);
        $mform->setType('teamsize', PARAM_TEXT);

        $mform->addElement('textarea', 'message', 'Votre besoin', ['rows' => 6, 'cols' => 60]);
        $mform->setType('message', PARAM_TEXT);
        $mform->addRule('message', 'Champ obligatoire', 'required', null, 'client');

        $mform->addElement('advcheckbox', 'consent', 'J’accepte d’être contacté(e).');
        $mform->setType('consent', PARAM_BOOL);
        $mform->addRule('consent', 'Veuillez cocher pour continuer', 'required', null, 'client');

        $this->add_action_buttons(false, 'Envoyer la demande');
    }
}
