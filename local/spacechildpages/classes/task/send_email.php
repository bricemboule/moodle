<?php
namespace local_spacechildpages\task;

defined('MOODLE_INTERNAL') || die();

class send_email extends \core\task\adhoc_task {
    public function execute() {
        $data = $this->get_custom_data();
        if (empty($data) || empty($data->toid) || empty($data->fromid) || empty($data->subject)) {
            return;
        }

        $touser = \core_user::get_user((int)$data->toid, '*', IGNORE_MISSING);
        $fromuser = \core_user::get_user((int)$data->fromid, '*', IGNORE_MISSING);
        if (empty($touser) || empty($fromuser)) {
            return;
        }

        if (empty($touser->email) || !validate_email($touser->email)) {
            return;
        }

        $body = isset($data->body) ? (string)$data->body : '';
        $bodyhtml = !empty($data->bodyhtml) ? (string)$data->bodyhtml : '';

        email_to_user($touser, $fromuser, (string)$data->subject, $body, $bodyhtml);
    }
}
