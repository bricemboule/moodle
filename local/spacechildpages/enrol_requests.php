<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->dirroot . '/login/lib.php');
require_once($CFG->dirroot . '/user/lib.php');

admin_externalpage_setup('local_spacechildpages_enrolrequests');

$PAGE->add_body_class('sc-enrol-requests');
$PAGE->requires->css(new moodle_url('/local/spacechildpages/style/enrol_requests.css'));

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$dbman = $DB->get_manager();
if (!$dbman->table_exists('local_spacechildpages_enrolreq')) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('enrolrequest:missingtable', 'local_spacechildpages'), 'error');
    echo $OUTPUT->footer();
    exit;
}

$columns = $DB->get_columns('local_spacechildpages_enrolreq');
if (!isset($columns['email'])) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('enrolrequest:missingtable', 'local_spacechildpages'), 'error');
    echo $OUTPUT->footer();
    exit;
}

$action = optional_param('action', '', PARAM_ALPHA);
$id = optional_param('id', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$filterstatus = optional_param('status', '', PARAM_ALPHA);
$filtercourseid = optional_param('courseid', 0, PARAM_INT);
$filtersearch = trim(optional_param('search', '', PARAM_RAW_TRIMMED));

$validstatuses = ['pending', 'approved', 'rejected'];
if ($filterstatus !== '' && !in_array($filterstatus, $validstatuses, true)) {
    $filterstatus = '';
}

$returnparams = [];
if ($filterstatus !== '') {
    $returnparams['status'] = $filterstatus;
}
if (!empty($filtercourseid)) {
    $returnparams['courseid'] = $filtercourseid;
}
if ($filtersearch !== '') {
    $returnparams['search'] = $filtersearch;
}

if (!empty($action) && !empty($id)) {
    $request = $DB->get_record('local_spacechildpages_enrolreq', ['id' => $id], '*', IGNORE_MISSING);
    if ($request) {
        if ($action === 'delete') {
            if (!$confirm) {
                echo $OUTPUT->header();
                $deleteurl = new moodle_url('/local/spacechildpages/enrol_requests.php', array_merge($returnparams, [
                    'action' => 'delete',
                    'id' => $id,
                    'confirm' => 1,
                    'sesskey' => sesskey(),
                ]));
                $cancelurl = new moodle_url('/local/spacechildpages/enrol_requests.php', $returnparams);
                echo $OUTPUT->confirm(
                    get_string('enrolrequest:confirmdelete', 'local_spacechildpages', format_string($request->fullname)),
                    $deleteurl,
                    $cancelurl
                );
                echo $OUTPUT->footer();
                exit;
            }

            if (confirm_sesskey()) {
                $DB->delete_records('local_spacechildpages_enrolreq', ['id' => $id]);
                redirect(
                    new moodle_url('/local/spacechildpages/enrol_requests.php', $returnparams),
                    get_string('enrolrequest:deleted', 'local_spacechildpages'),
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            }
        }

        if (($action === 'approve' || $action === 'reject') && confirm_sesskey()) {
            $message = get_string('enrolrequest:updated', 'local_spacechildpages');
            $level = \core\output\notification::NOTIFY_SUCCESS;

            if ($action === 'approve') {
                $enrolerror = '';
                $enrolmessage = '';
                $usercreated = false;

                $course = null;
                if (!empty($request->courseid)) {
                    $course = $DB->get_record('course', ['id' => $request->courseid], 'id,fullname', IGNORE_MISSING);
                }

                $user = null;
                if (!empty($request->userid)) {
                    $user = \core_user::get_user($request->userid, '*', IGNORE_MISSING);
                    if ($user && !empty($user->deleted)) {
                        $user = null;
                    }
                }
                if (!$user && !empty($request->email)) {
                    $matched = \core_user::get_user_by_email($request->email, '*', null, IGNORE_MISSING);
                    if ($matched && empty($matched->deleted)) {
                        $user = $matched;
                        $request->userid = (int)$matched->id;
                        $DB->update_record('local_spacechildpages_enrolreq', $request);
                    }
                }

                if (!$user && !empty($request->email)) {
                    $email = clean_param($request->email, PARAM_EMAIL);
                    if ($email !== '') {
                        $fullname = trim((string)$request->fullname);
                        $firstname = '';
                        $lastname = '';

                        if ($fullname !== '') {
                            $parts = preg_split('/\s+/', $fullname, -1, PREG_SPLIT_NO_EMPTY);
                            $firstname = (string)array_shift($parts);
                            $lastname = trim(implode(' ', $parts));
                        }

                        if ($firstname === '' || $lastname === '') {
                            $localpart = preg_replace('/@.*$/', '', $email);
                            $nameparts = preg_split('/[._-]+/', (string)$localpart, -1, PREG_SPLIT_NO_EMPTY);
                            if ($firstname === '' && !empty($nameparts)) {
                                $firstname = (string)array_shift($nameparts);
                            }
                            if ($lastname === '' && !empty($nameparts)) {
                                $lastname = trim(implode(' ', $nameparts));
                            }
                        }

                        $firstname = trim($firstname);
                        $lastname = trim($lastname);
                        if ($firstname === '') {
                            $firstname = 'User';
                        }
                        if ($lastname === '') {
                            $lastname = $firstname;
                        }

                        $baseusername = preg_replace('/@.*$/', '', core_text::strtolower($email));
                        $baseusername = core_user::clean_field($baseusername, 'username');
                        if ($baseusername === '') {
                            $baseusername = 'user';
                        }
                        $username = $baseusername;
                        $suffix = 0;
                        while ($DB->record_exists('user', ['username' => $username, 'mnethostid' => $CFG->mnet_localhost_id])) {
                            $suffix++;
                            $username = $baseusername . $suffix;
                        }

                        try {
                            $newuser = new stdClass();
                            $newuser->auth = 'manual';
                            $newuser->confirmed = 1;
                            $newuser->mnethostid = $CFG->mnet_localhost_id;
                            $newuser->username = $username;
                            $newuser->firstname = $firstname;
                            $newuser->lastname = $lastname;
                            $newuser->email = $email;
                            // Pas de mot de passe - l'utilisateur devra le créer via le lien
                            $newuser->password = 'not cached';

                            $newuserid = user_create_user($newuser, false, false);
                            $user = \core_user::get_user($newuserid, '*', IGNORE_MISSING);
                            if ($user) {
                                $request->userid = (int)$user->id;
                                $DB->update_record('local_spacechildpages_enrolreq', $request);
                                $usercreated = true;
                            }
                        } catch (Exception $e) {
                            $enrolerror = $e->getMessage();
                        }
                    }
                }

                if ($enrolerror === '') {
                    if ($course && $user) {
                        $context = context_course::instance($course->id);
                        if (!is_enrolled($context, $user->id, '', true)) {
                            $instances = enrol_get_instances($course->id, false);
                            $manualinstance = null;
                            foreach ($instances as $instance) {
                                if ($instance->enrol === 'manual' && (int)$instance->status === ENROL_INSTANCE_ENABLED) {
                                    $manualinstance = $instance;
                                    break;
                                }
                            }

                            $plugin = enrol_get_plugin('manual');
                            if (empty($plugin)) {
                                $enrolerror = get_string('enrolrequest:manual_missing', 'local_spacechildpages');
                            } else if (empty($manualinstance)) {
                                $enrolerror = get_string('enrolrequest:manual_instance_missing', 'local_spacechildpages');
                            } else {
                                $roleid = (int)$manualinstance->roleid;
                                if (!$roleid) {
                                    $roleid = (int)get_config('enrol_manual', 'roleid');
                                }
                                if (!$roleid) {
                                    $roles = get_archetype_roles('student');
                                    if (!empty($roles)) {
                                        $role = reset($roles);
                                        $roleid = (int)$role->id;
                                    }
                                }

                                // SOLUTION: Désactiver temporairement l'email de bienvenue
                                $sendwelcome = $manualinstance->customint4 ?? 0;
                                if ($sendwelcome) {
                                    $manualinstance->customint4 = 0;
                                    $DB->update_record('enrol', $manualinstance);
                                }

                                try {
                                    $plugin->enrol_user($manualinstance, $user->id, $roleid ?: null, time());
                                    $enrolmessage = get_string('enrolrequest:approved_enrolled', 'local_spacechildpages');
                                } catch (Exception $e) {
                                    $enrolerror = "Erreur lors de l'inscription: " . $e->getMessage();
                                }

                                // Restaurer le paramètre
                                if ($sendwelcome) {
                                    $manualinstance->customint4 = $sendwelcome;
                                    $DB->update_record('enrol', $manualinstance);
                                }
                            }
                        } else {
                            $enrolmessage = get_string('enrolrequest:approved_already', 'local_spacechildpages');
                        }
                    } else {
                        if (!$course) {
                            $enrolmessage = get_string('enrolrequest:approved_noenrol', 'local_spacechildpages');
                        } else {
                            $emailhint = !empty($request->email) ? $request->email : '-';
                            $enrolerror = get_string('enrolrequest:user_notfound', 'local_spacechildpages', $emailhint);
                        }
                    }
                }

                if ($enrolerror !== '') {
                    redirect(
                        new moodle_url('/local/spacechildpages/enrol_requests.php'),
                        $enrolerror,
                        null,
                        \core\output\notification::NOTIFY_ERROR
                    );
                }

                $request->status = 'approved';
                $request->timemodified = time();
                $DB->update_record('local_spacechildpages_enrolreq', $request);
                $message = $enrolmessage !== '' ? $enrolmessage : $message;

                // ============================================================================
                // ENVOI EMAIL - VERSION DIRECTE (fonctionne local + production)
                // ============================================================================
                if ($user && !empty($user->email) && validate_email($user->email)) {
                    $sitename = format_string($SITE->shortname ?: $SITE->fullname);
                    $coursename = $course ? format_string($course->fullname) : '-';
                    $courseurl = $course ? (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false) : '#';
                    $loginurl = (new moodle_url('/login/index.php'))->out(false);
                    $support = \core_user::get_support_user();

                    $subject = "Inscription approuvee - {$sitename}";
                    $fullname = fullname($user);

                    if ($usercreated) {
                        $resetrecord = core_login_generate_password_reset($user);
                        // Construction manuelle pour éviter les problèmes en local
                        $reseturl = $CFG->wwwroot . '/login/forgot_password.php?token=' . $resetrecord->token;

                        // Version TEXTE de l'email (sans emojis pour compatibilité)
                        $messagetext = "Bonjour {$fullname},

Felicitations ! Votre demande d'inscription a ete approuvee.

CREATION DE VOTRE MOT DE PASSE
-------------------------------
Pour activer votre compte, vous devez d'abord creer votre mot de passe en cliquant sur le lien ci-dessous :

{$reseturl}

IMPORTANT :
-----------
- Ce lien est valide pendant 24 heures
- Vous devrez creer un mot de passe securise
- Apres avoir cree votre mot de passe, vous pourrez vous connecter

VOS IDENTIFIANTS
----------------
Username: {$user->username}
Email: {$user->email}
URL de connexion: {$loginurl}

VOTRE COURS
-----------
{$coursename}
{$courseurl}

Si vous rencontrez des difficultes, n'hesitez pas a nous contacter.

Cordialement,
L'equipe {$sitename}";

                        // Version HTML de l'email
                        $messagehtml = "
<html>
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
</head>
<body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: #f4f4f4; padding: 20px;'>
        <tr>
            <td align=\"center\">
                <table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                    
                    <!-- Header -->
                    <tr>
                        <td style='background-color: #28a745; color: white; padding: 30px 20px; text-align: center;'>
                            <h1 style='margin: 0; font-size: 24px;'>✅ Inscription approuvée</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style='padding: 30px 20px;'>
                            <p style='font-size: 16px; color: #333; margin: 0 0 15px 0;'>Bonjour <strong>{$fullname}</strong>,</p>
                            <p style='font-size: 16px; color: #333; margin: 0 0 25px 0;'>Félicitations ! Votre demande d'inscription a été approuvée.</p>
                            
                            <!-- Password Creation Section -->
                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: #e7f3ff; border-left: 4px solid #007bff; margin: 0 0 20px 0;'>
                                <tr>
                                    <td style='padding: 20px;'>
                                        <h2 style='color: #007bff; margin: 0 0 15px 0; font-size: 18px;'>🔐 CRÉATION DE VOTRE MOT DE PASSE</h2>
                                        <p style='font-size: 15px; color: #333; margin: 0 0 20px 0;'>Pour activer votre compte, vous devez d'abord créer votre mot de passe :</p>
                                        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                            <tr>
                                                <td align=\"center\" style='padding: 10px 0;'>
                                                    <a href='{$reseturl}' style='display: inline-block; background-color: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>
                                                        👉 Créer mon mot de passe
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                        <p style='font-size: 13px; color: #666; margin: 15px 0 0 0;'>Ou copiez ce lien dans votre navigateur :</p>
                                        <p style='font-size: 12px; color: #007bff; word-break: break-all; margin: 5px 0 0 0;'>
                                            <a href='{$reseturl}' style='color: #007bff;'>{$reseturl}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Important Notice -->
                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: #fff3cd; border-left: 4px solid #ffc107; margin: 0 0 20px 0;'>
                                <tr>
                                    <td style='padding: 15px 20px;'>
                                        <h3 style='color: #856404; margin: 0 0 10px 0; font-size: 16px;'>⚠️ IMPORTANT</h3>
                                        <ul style='margin: 0; padding: 0 0 0 20px; color: #856404;'>
                                            <li style='margin: 5px 0;'>Ce lien est valide pendant <strong>24 heures</strong></li>
                                            <li style='margin: 5px 0;'>Vous devrez créer un mot de passe sécurisé</li>
                                            <li style='margin: 5px 0;'>Après avoir créé votre mot de passe, vous pourrez vous connecter</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Credentials -->
                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: #f8f9fa; border-radius: 5px; margin: 0 0 20px 0;'>
                                <tr>
                                    <td style='padding: 20px;'>
                                        <h3 style='color: #333; margin: 0 0 15px 0; font-size: 16px;'>📋 VOS IDENTIFIANTS</h3>
                                        <table width=\"100%\" cellpadding=\"8\" cellspacing=\"0\">
                                            <tr style='border-bottom: 1px solid #ddd;'>
                                                <td style='font-weight: bold; color: #555;'>Username:</td>
                                                <td style='color: #333;'>{$user->username}</td>
                                            </tr>
                                            <tr style='border-bottom: 1px solid #ddd;'>
                                                <td style='font-weight: bold; color: #555;'>Email:</td>
                                                <td style='color: #333;'>{$user->email}</td>
                                            </tr>
                                            <tr>
                                                <td style='font-weight: bold; color: #555;'>Connexion:</td>
                                                <td><a href='{$loginurl}' style='color: #007bff;'>{$loginurl}</a></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Course -->
                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: #f8f9fa; border-radius: 5px; margin: 0 0 20px 0;'>
                                <tr>
                                    <td style='padding: 20px;'>
                                        <h3 style='color: #333; margin: 0 0 10px 0; font-size: 16px;'>📚 VOTRE COURS</h3>
                                        <p style='font-size: 16px; font-weight: bold; color: #333; margin: 0 0 15px 0;'>{$coursename}</p>
                                        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                            <tr>
                                                <td align=\"center\">
                                                    <a href='{$courseurl}' style='display: inline-block; background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                                                        Accéder au cours
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style='background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 2px solid #ddd;'>
                            <p style='font-size: 14px; color: #666; margin: 0 0 10px 0;'>
                                Si vous rencontrez des difficultés, n'hésitez pas à nous contacter.
                            </p>
                            <p style='font-size: 14px; color: #666; margin: 0;'>
                                Cordialement,<br>
                                <strong>L'équipe {$sitename}</strong>
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>";
                    } else {
                        // Version TEXTE de l'email (compte existant)
                        $messagetext = "Bonjour {$fullname},

Votre demande d'inscription a ete approuvee.

VOTRE COURS
-----------
{$coursename}
{$courseurl}

Si vous rencontrez des difficultes, n'hesitez pas a nous contacter.

Cordialement,
L'equipe {$sitename}";

                        // Version HTML de l'email (compte existant)
                        $messagehtml = "
<html>
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
</head>
<body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: #f4f4f4; padding: 20px;'>
        <tr>
            <td align=\"center\">
                <table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                    
                    <!-- Header -->
                    <tr>
                        <td style='background-color: #28a745; color: white; padding: 30px 20px; text-align: center;'>
                            <h1 style='margin: 0; font-size: 24px;'>✅ Inscription approuvée</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style='padding: 30px 20px;'>
                            <p style='font-size: 16px; color: #333; margin: 0 0 15px 0;'>Bonjour <strong>{$fullname}</strong>,</p>
                            <p style='font-size: 16px; color: #333; margin: 0 0 25px 0;'>Votre demande d'inscription a été approuvée.</p>

                            <!-- Course -->
                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style='background-color: #f8f9fa; border-radius: 5px; margin: 0 0 20px 0;'>
                                <tr>
                                    <td style='padding: 20px;'>
                                        <h3 style='color: #333; margin: 0 0 10px 0; font-size: 16px;'>📚 VOTRE COURS</h3>
                                        <p style='font-size: 16px; font-weight: bold; color: #333; margin: 0 0 15px 0;'>{$coursename}</p>
                                        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                            <tr>
                                                <td align=\"center\">
                                                    <a href='{$courseurl}' style='display: inline-block; background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                                                        Accéder au cours
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style='background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 2px solid #ddd;'>
                            <p style='font-size: 14px; color: #666; margin: 0 0 10px 0;'>
                                Si vous rencontrez des difficultés, n'hésitez pas à nous contacter.
                            </p>
                            <p style='font-size: 14px; color: #666; margin: 0;'>
                                Cordialement,<br>
                                <strong>L'équipe {$sitename}</strong>
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>";
                    }

                    // ENVOI DIRECT de l'email (plus fiable que les tâches adhoc)
                    try {
                        $emailsent = email_to_user($user, $support, $subject, $messagetext, $messagehtml);
                        
                        if ($emailsent) {
                            // Log succès dans error.log (visible en production)
                            error_log("[SPACECHILDPAGES] ✅ Email d'approbation envoyé avec succès à {$user->email} (Request ID: {$request->id}, User ID: {$user->id})");
                        } else {
                            // Log échec
                            error_log("[SPACECHILDPAGES] ❌ ÉCHEC d'envoi de l'email à {$user->email} (Request ID: {$request->id}, User ID: {$user->id}) - email_to_user returned false");
                            debugging("Failed to send approval email to user {$user->id}", DEBUG_DEVELOPER);
                        }
                    } catch (Exception $e) {
                        // Log exception détaillée
                        error_log("[SPACECHILDPAGES] ❌ EXCEPTION lors de l'envoi email (Request ID: {$request->id}): " . $e->getMessage());
                        error_log("[SPACECHILDPAGES] Stack trace: " . $e->getTraceAsString());
                        debugging("Exception sending approval email: " . $e->getMessage(), DEBUG_DEVELOPER);
                    }
                }
                // ============================================================================
                
            } else {
                $request->status = 'rejected';
                $request->timemodified = time();
                $DB->update_record('local_spacechildpages_enrolreq', $request);
            }

            redirect(
                new moodle_url('/local/spacechildpages/enrol_requests.php', $returnparams),
                $message,
                null,
                $level
            );
        }
    }
}

$PAGE->set_title(get_string('enrolrequests', 'local_spacechildpages'));
$PAGE->set_heading(get_string('enrolrequests', 'local_spacechildpages'));

echo $OUTPUT->header();

$courselist = $DB->get_records_sql(
    "SELECT id, fullname
       FROM {course}
      WHERE id <> :siteid
   ORDER BY fullname",
    ['siteid' => SITEID]
);

$statusoptions = [
    '' => get_string('enrolrequest:filter_status_all', 'local_spacechildpages'),
    'pending' => get_string('enrolrequest:status_pending', 'local_spacechildpages'),
    'approved' => get_string('enrolrequest:status_approved', 'local_spacechildpages'),
    'rejected' => get_string('enrolrequest:status_rejected', 'local_spacechildpages'),
];

$courseoptions = [
    0 => get_string('enrolrequest:filter_course_all', 'local_spacechildpages'),
];
foreach ($courselist as $courseitem) {
    $courseoptions[$courseitem->id] = format_string($courseitem->fullname);
}

echo html_writer::start_tag('form', [
    'class' => 'sc-enrol-requests__filters',
    'method' => 'get',
    'action' => (new moodle_url('/local/spacechildpages/enrol_requests.php'))->out(false),
]);

echo html_writer::start_tag('div', ['class' => 'sc-enrol-requests__filters-row']);

echo html_writer::start_tag('div', ['class' => 'sc-enrol-requests__filter']);
echo html_writer::tag('label', get_string('enrolrequest:filter_status', 'local_spacechildpages'), [
    'class' => 'sc-enrol-requests__filter-label',
]);
echo html_writer::select(
    $statusoptions,
    'status',
    $filterstatus,
    false,
    ['class' => 'sc-enrol-requests__filter-select']
);
echo html_writer::end_tag('div');

echo html_writer::start_tag('div', ['class' => 'sc-enrol-requests__filter']);
echo html_writer::tag('label', get_string('enrolrequest:filter_course', 'local_spacechildpages'), [
    'class' => 'sc-enrol-requests__filter-label',
]);
echo html_writer::select(
    $courseoptions,
    'courseid',
    $filtercourseid,
    false,
    ['class' => 'sc-enrol-requests__filter-select']
);
echo html_writer::end_tag('div');

echo html_writer::start_tag('div', ['class' => 'sc-enrol-requests__filter sc-enrol-requests__filter--search']);
echo html_writer::tag('label', get_string('enrolrequest:filter_search', 'local_spacechildpages'), [
    'class' => 'sc-enrol-requests__filter-label',
]);
echo html_writer::empty_tag('input', [
    'type' => 'text',
    'name' => 'search',
    'value' => $filtersearch,
    'placeholder' => get_string('enrolrequest:filter_search_placeholder', 'local_spacechildpages'),
    'class' => 'sc-enrol-requests__filter-input',
]);
echo html_writer::end_tag('div');

echo html_writer::start_tag('div', ['class' => 'sc-enrol-requests__filter-actions']);
echo html_writer::empty_tag('input', [
    'type' => 'submit',
    'value' => get_string('enrolrequest:filter_apply', 'local_spacechildpages'),
    'class' => 'sc-enrol-requests__filter-submit',
]);
echo html_writer::link(
    new moodle_url('/local/spacechildpages/enrol_requests.php'),
    get_string('enrolrequest:filter_reset', 'local_spacechildpages'),
    ['class' => 'sc-enrol-requests__filter-reset']
);
echo html_writer::end_tag('div');

echo html_writer::end_tag('div');
echo html_writer::end_tag('form');

$conditions = [];
$params = [];
if ($filterstatus !== '') {
    $conditions[] = 'status = :status';
    $params['status'] = $filterstatus;
}
if (!empty($filtercourseid)) {
    $conditions[] = 'courseid = :courseid';
    $params['courseid'] = $filtercourseid;
}
if ($filtersearch !== '') {
    $searchterm = '%' . $DB->sql_like_escape($filtersearch) . '%';
    $conditions[] = '(' . $DB->sql_like('fullname', ':search', false) . ' OR ' . $DB->sql_like('email', ':search', false) . ')';
    $params['search'] = $searchterm;
}

$wheresql = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
$records = $DB->get_records_sql(
    "SELECT *
       FROM {local_spacechildpages_enrolreq}
     {$wheresql}
   ORDER BY timecreated DESC",
    $params
);

if (empty($records)) {
    $hasfilters = ($filterstatus !== '' || !empty($filtercourseid) || $filtersearch !== '');
    $message = $hasfilters
        ? get_string('enrolrequest:norequests_filtered', 'local_spacechildpages')
        : get_string('enrolrequest:norequests', 'local_spacechildpages');
    echo $OUTPUT->notification($message, 'info');
    echo $OUTPUT->footer();
    exit;
}

$courseids = [];
foreach ($records as $record) {
    if (!empty($record->courseid)) {
        $courseids[] = (int)$record->courseid;
    }
}
$courseids = array_values(array_unique($courseids));
$courses = [];
if (!empty($courseids)) {
    $courses = $DB->get_records_list('course', 'id', $courseids, '', 'id,fullname');
}

$table = new html_table();
$table->head = [
    get_string('enrolrequest:date', 'local_spacechildpages'),
    get_string('enrolrequest:course_col', 'local_spacechildpages'),
    get_string('enrolrequest:fullname_col', 'local_spacechildpages'),
    get_string('enrolrequest:email_col', 'local_spacechildpages'),
    get_string('enrolrequest:organisation_col', 'local_spacechildpages'),
    get_string('enrolrequest:phone_col', 'local_spacechildpages'),
    get_string('enrolrequest:position_col', 'local_spacechildpages'),
    get_string('enrolrequest:message_col', 'local_spacechildpages'),
    get_string('enrolrequest:status_col', 'local_spacechildpages'),
    get_string('actions', 'local_spacechildpages'),
];

foreach ($records as $record) {
    $coursename = '-';
    if (!empty($record->courseid) && isset($courses[$record->courseid])) {
        $coursename = format_string($courses[$record->courseid]->fullname);
    }

    $statuskey = 'enrolrequest:status_' . $record->status;
    $status = get_string($statuskey, 'local_spacechildpages');

    $approveurl = new moodle_url('/local/spacechildpages/enrol_requests.php', array_merge($returnparams, [
        'action' => 'approve',
        'id' => $record->id,
        'sesskey' => sesskey(),
    ]));
    $rejecturl = new moodle_url('/local/spacechildpages/enrol_requests.php', array_merge($returnparams, [
        'action' => 'reject',
        'id' => $record->id,
        'sesskey' => sesskey(),
    ]));
    $deleteurl = new moodle_url('/local/spacechildpages/enrol_requests.php', array_merge($returnparams, [
        'action' => 'delete',
        'id' => $record->id,
        'sesskey' => sesskey(),
    ]));

    $actions = html_writer::link($approveurl, get_string('enrolrequest:approve', 'local_spacechildpages'))
        . ' | ' . html_writer::link($rejecturl, get_string('enrolrequest:reject', 'local_spacechildpages'))
        . ' | ' . html_writer::link($deleteurl, get_string('enrolrequest:delete', 'local_spacechildpages'));

    if (!empty($record->userid)) {
        $progressurl = new moodle_url('/local/spacechildpages/progress_dashboard.php', [
            'userid' => (int)$record->userid,
            'courseid' => (int)$record->courseid,
        ]);
        $actions .= ' | ' . html_writer::link(
            $progressurl,
            get_string('enrolrequest:progress_link', 'local_spacechildpages')
        );
    }

    $table->data[] = [
        userdate((int)$record->timecreated),
        $coursename,
        format_string($record->fullname),
        s($record->email),
        format_string($record->organisation),
        s($record->phone),
        s($record->position),
        s($record->message),
        $status,
        $actions,
    ];
}

$existingclasses = $table->attributes['class'] ?? '';
$table->attributes['class'] = trim($existingclasses . ' sc-enrol-requests-table');

echo html_writer::start_tag('div', ['class' => 'sc-table-wrap']);
echo html_writer::table($table);
echo html_writer::end_tag('div');
echo $OUTPUT->footer();
