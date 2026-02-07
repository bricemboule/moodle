<?php
// /local/spacechildpages/classes/observer.php

namespace local_spacechildpages;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/completionlib.php');
require_once($CFG->dirroot . '/grade/querylib.php');

/**
 * Observateur d'événements pour le suivi d'achèvement des cours
 * 
 * Gère les notifications automatiques aux enseignants et à l'administration
 * lorsqu'un apprenant termine un cours ou atteint un jalon de progression.
 *
 * @package    local_spacechildpages
 * @copyright  2026 Qualisys
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {
    
    /**
     * Gère l'événement d'achèvement complet d'un cours
     * 
     * Déclenché quand un apprenant complète tous les critères d'achèvement d'un cours.
     * Envoie des notifications aux enseignants et administrateurs.
     * 
     * @param \core\event\course_completed $event L'événement d'achèvement
     */
    public static function course_completed(\core\event\course_completed $event) {
        global $DB;
        
        try {
            // Récupérer les données de l'événement
            $completiondata = $event->get_record_snapshot('course_completions', $event->objectid);
            $course = $DB->get_record('course', ['id' => $completiondata->course], '*', MUST_EXIST);
            $user = $DB->get_record('user', ['id' => $completiondata->userid], '*', MUST_EXIST);
            
            // Log pour débogage
            error_log(sprintf(
                '[COMPLETION] User %s (%d) completed course %s (%d)',
                fullname($user),
                $user->id,
                $course->fullname,
                $course->id
            ));
            
            // Récupérer les destinataires des notifications
            $recipients = self::get_notification_recipients($course->id);
            
            if (empty($recipients)) {
                error_log('[COMPLETION] No recipients found for course ' . $course->id);
                return;
            }
            
            // Préparer les données pour le message
            $messagedata = self::prepare_completion_data($user, $course, $completiondata);
            
            // Envoyer les notifications
            $sent = 0;
            foreach ($recipients as $recipient) {
                if (self::send_completion_notification($recipient, $messagedata)) {
                    $sent++;
                }
            }
            
            error_log(sprintf('[COMPLETION] Sent %d notification(s) for course completion', $sent));
            
            // Enregistrer dans la table personnalisée
            self::log_completion($user, $course, $completiondata, $messagedata);
            
        } catch (\Exception $e) {
            error_log('[COMPLETION ERROR] ' . $e->getMessage());
            debugging('Error in course_completed observer: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }
    }
    
    /**
     * Gère la mise à jour d'achèvement d'une activité
     * 
     * Déclenché quand une activité est marquée comme complétée.
     * Calcule la progression globale et notifie aux jalons importants.
     * 
     * @param \core\event\course_module_completion_updated $event
     */
    public static function module_completion_updated(\core\event\course_module_completion_updated $event) {
        global $DB;
        
        try {
            $userid = $event->relateduserid;
            $courseid = $event->courseid;
            
            // Récupérer le cours et l'utilisateur
            $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
            $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
            
            // Calculer le pourcentage de progression
            $percentage = self::calculate_course_progress($course, $userid);
            
            if ($percentage === null) {
                return; // Progression non disponible
            }
            
            // Définir les jalons de notification : 25%, 50%, 75%
            $milestones = [25, 50, 75];
            $roundedpercentage = round($percentage);
            
            // Vérifier si on a atteint un jalon
            if (in_array($roundedpercentage, $milestones)) {
                // Vérifier si on n'a pas déjà notifié pour ce jalon
                $alreadynotified = $DB->record_exists('local_spacechildpages_progress', [
                    'userid' => $userid,
                    'courseid' => $courseid,
                    'milestone' => $roundedpercentage
                ]);
                
                if (!$alreadynotified) {
                    self::send_progress_notification($user, $course, $roundedpercentage);
                    
                    // Enregistrer qu'on a notifié pour ce jalon
                    $record = new \stdClass();
                    $record->userid = $userid;
                    $record->courseid = $courseid;
                    $record->milestone = $roundedpercentage;
                    $record->timenotified = time();
                    $DB->insert_record('local_spacechildpages_progress', $record);
                }
            }
            
        } catch (\Exception $e) {
            error_log('[PROGRESS ERROR] ' . $e->getMessage());
            debugging('Error in module_completion_updated observer: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }
    }
    
    /**
     * Récupère les destinataires des notifications pour un cours
     * 
     * @param int $courseid ID du cours
     * @return array Tableau d'objets utilisateur (enseignants + admins)
     */
    private static function get_notification_recipients($courseid) {
        $recipients = [];
        
        // Récupérer les enseignants du cours
        $context = \context_course::instance($courseid);
        $teachers = get_enrolled_users($context, 'mod/assign:grade'); // Capacité typique des enseignants
        
        foreach ($teachers as $teacher) {
            $recipients[$teacher->id] = $teacher;
        }
        
        // Récupérer les administrateurs du site
        $admins = get_admins();
        foreach ($admins as $admin) {
            $recipients[$admin->id] = $admin;
        }
        
        return $recipients;
    }
    
    /**
     * Prépare les données pour le message de complétion
     * 
     * @param \stdClass $user L'utilisateur
     * @param \stdClass $course Le cours
     * @param \stdClass $completion Les données de complétion
     * @return \stdClass Données formatées pour le message
     */
    private static function prepare_completion_data($user, $course, $completion) {
        global $DB;
        
        $data = new \stdClass();
        $data->user = $user;
        $data->course = $course;
        $data->completion = $completion;
        
        // Récupérer la note finale
        $gradeitem = \grade_item::fetch_course_item($course->id);
        if ($gradeitem) {
            $grade = new \grade_grade(['itemid' => $gradeitem->id, 'userid' => $user->id]);
            $grade->load_grade_item();
            $data->finalgrade = $grade->finalgrade;
            $data->grademax = $gradeitem->grademax;
            $data->gradepercentage = $gradeitem->grademax > 0 
                ? round(($grade->finalgrade / $gradeitem->grademax) * 100, 2) 
                : null;
        } else {
            $data->finalgrade = null;
            $data->grademax = null;
            $data->gradepercentage = null;
        }
        
        // Calculer le temps passé (si disponible)
        $data->timespent = self::calculate_time_spent($user->id, $course->id);
        
        // Compter les activités complétées
        $data->activitiescompleted = self::count_completed_activities($user->id, $course->id);
        
        return $data;
    }
    
    /**
     * Envoie une notification d'achèvement de cours
     * 
     * @param \stdClass $recipient Destinataire de la notification
     * @param \stdClass $data Données de complétion
     * @return bool Succès de l'envoi
     */
    private static function send_completion_notification($recipient, $data) {
        global $CFG;
        
        $userfullname = fullname($data->user);
        $coursename = format_string($data->course->fullname);
        
        // Préparer le sujet
        $subject = get_string('completion_notification_subject', 'local_spacechildpages', [
            'username' => $userfullname,
            'coursename' => $coursename
        ]);
        
        // Si la chaîne n'existe pas, utiliser un texte par défaut
        if (strpos($subject, '[[') !== false) {
            $subject = "✅ Achèvement de cours : {$userfullname} - {$coursename}";
        }
        
        // Préparer le message texte
        $messagetext = self::format_completion_message_text($data);
        
        // Préparer le message HTML
        $messagehtml = self::format_completion_message_html($data);
        
        // Créer le message
        $message = new \core\message\message();
        $message->component = 'local_spacechildpages';
        $message->name = 'coursecompletion';
        $message->userfrom = \core_user::get_noreply_user();
        $message->userto = $recipient;
        $message->subject = $subject;
        $message->fullmessage = $messagetext;
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = $messagehtml;
        $message->smallmessage = substr($messagetext, 0, 200);
        $message->notification = 1;
        
        // Ajouter un lien vers le rapport de complétion
        $message->contexturl = new \moodle_url('/report/completion/user.php', [
            'course' => $data->course->id,
            'user' => $data->user->id
        ]);
        $message->contexturlname = get_string('viewcompletionreport', 'local_spacechildpages');
        if (strpos($message->contexturlname, '[[') !== false) {
            $message->contexturlname = 'Voir le rapport complet';
        }
        
        // Envoyer le message
        $messageid = message_send($message);
        
        if (!$messageid) {
            error_log("[COMPLETION] Failed to send notification to user {$recipient->id}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Formate le message de complétion (version texte)
     * 
     * @param \stdClass $data Données de complétion
     * @return string Message formaté
     */
    private static function format_completion_message_text($data) {
        $message = "🎓 ACHÈVEMENT DE COURS\n";
        $message .= str_repeat('=', 50) . "\n\n";
        
        $message .= "Apprenant : " . fullname($data->user) . "\n";
        $message .= "Email : " . $data->user->email . "\n";
        $message .= "Cours : " . format_string($data->course->fullname) . "\n";
        $message .= "Date d'achèvement : " . userdate($data->completion->timecompleted, '%d/%m/%Y à %H:%M') . "\n";
        
        $message .= "\n" . str_repeat('-', 50) . "\n";
        $message .= "STATISTIQUES\n";
        $message .= str_repeat('-', 50) . "\n\n";
        
        if ($data->gradepercentage !== null) {
            $message .= "Note finale : " . $data->gradepercentage . "%";
            if ($data->finalgrade !== null && $data->grademax !== null) {
                $message .= " (" . round($data->finalgrade, 2) . "/" . $data->grademax . ")";
            }
            $message .= "\n";
        }
        
        if ($data->activitiescompleted !== null) {
            $message .= "Activités complétées : " . $data->activitiescompleted . "\n";
        }
        
        if ($data->timespent !== null) {
            $message .= "Temps estimé passé : " . self::format_duration($data->timespent) . "\n";
        }
        
        $message .= "\n" . str_repeat('=', 50) . "\n";
        $message .= "📊 Consultez le rapport détaillé pour plus d'informations.\n";
        
        return $message;
    }
    
    /**
     * Formate le message de complétion (version HTML)
     * 
     * @param \stdClass $data Données de complétion
     * @return string Message HTML formaté
     */
    private static function format_completion_message_html($data) {
        $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
        
        // En-tête
        $html .= '<div style="background: linear-gradient(135deg, #297664, #34a085); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">';
        $html .= '<h1 style="margin: 0; font-size: 24px;">🎓 Achèvement de cours</h1>';
        $html .= '</div>';
        
        // Contenu principal
        $html .= '<div style="background: #f5f7f7; padding: 30px; border-radius: 0 0 10px 10px;">';
        
        // Informations apprenant
        $html .= '<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">';
        $html .= '<h2 style="color: #297664; margin-top: 0;">Informations de l\'apprenant</h2>';
        $html .= '<p><strong>Nom :</strong> ' . fullname($data->user) . '</p>';
        $html .= '<p><strong>Email :</strong> ' . $data->user->email . '</p>';
        $html .= '<p><strong>Cours :</strong> ' . format_string($data->course->fullname) . '</p>';
        $html .= '<p><strong>Date d\'achèvement :</strong> ' . userdate($data->completion->timecompleted, '%d/%m/%Y à %H:%M') . '</p>';
        $html .= '</div>';
        
        // Statistiques
        $html .= '<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">';
        $html .= '<h2 style="color: #297664; margin-top: 0;">Statistiques</h2>';
        
        if ($data->gradepercentage !== null) {
            $color = $data->gradepercentage >= 50 ? '#4caf50' : '#f44336';
            $html .= '<div style="margin-bottom: 15px;">';
            $html .= '<p style="margin: 5px 0;"><strong>Note finale :</strong></p>';
            $html .= '<div style="background: #e0e0e0; border-radius: 20px; height: 30px; position: relative; overflow: hidden;">';
            $html .= '<div style="background: ' . $color . '; width: ' . $data->gradepercentage . '%; height: 100%; border-radius: 20px; display: flex; align-items: center; justify-content: center;">';
            $html .= '<span style="color: white; font-weight: bold; font-size: 14px;">' . $data->gradepercentage . '%</span>';
            $html .= '</div></div>';
            if ($data->finalgrade !== null && $data->grademax !== null) {
                $html .= '<p style="margin: 5px 0; font-size: 12px; color: #666;">' . round($data->finalgrade, 2) . ' / ' . $data->grademax . '</p>';
            }
            $html .= '</div>';
        }
        
        if ($data->activitiescompleted !== null) {
            $html .= '<p>✅ <strong>Activités complétées :</strong> ' . $data->activitiescompleted . '</p>';
        }
        
        if ($data->timespent !== null) {
            $html .= '<p>⏱️ <strong>Temps estimé passé :</strong> ' . self::format_duration($data->timespent) . '</p>';
        }
        
        $html .= '</div>';
        
        // Bouton d'action
        $reporturl = new \moodle_url('/report/completion/user.php', [
            'course' => $data->course->id,
            'user' => $data->user->id
        ]);
        
        $html .= '<div style="text-align: center; margin-top: 30px;">';
        $html .= '<a href="' . $reporturl->out(false) . '" style="display: inline-block; background: #b53a3d; color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 16px;">📊 Voir le rapport complet</a>';
        $html .= '</div>';
        
        $html .= '</div>'; // Fin contenu principal
        
        // Pied de page
        $html .= '<div style="text-align: center; padding: 20px; color: #666; font-size: 12px;">';
        $html .= '<p>Ce message a été généré automatiquement par la plateforme.</p>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Enregistre l'achèvement dans la table personnalisée
     * 
     * @param \stdClass $user
     * @param \stdClass $course
     * @param \stdClass $completion
     * @param \stdClass $data
     */
    private static function log_completion($user, $course, $completion, $data) {
        global $DB;
        
        // Vérifier si la table existe
        $dbman = $DB->get_manager();
        if (!$dbman->table_exists('local_spacechildpages_completions')) {
            error_log('[COMPLETION] Table local_spacechildpages_completions does not exist');
            return;
        }
        
        try {
            // Vérifier si on n'a pas déjà enregistré cet achèvement
            $exists = $DB->record_exists('local_spacechildpages_completions', [
                'userid' => $user->id,
                'courseid' => $course->id
            ]);
            
            $record = new \stdClass();
            $record->userid = $user->id;
            $record->courseid = $course->id;
            $record->timecompleted = $completion->timecompleted;
            $record->grade = $data->gradepercentage;
            $record->notified = time();
            $record->timemodified = time();
            
            if ($exists) {
                // Mettre à jour l'enregistrement existant
                $existing = $DB->get_record('local_spacechildpages_completions', [
                    'userid' => $user->id,
                    'courseid' => $course->id
                ]);
                $record->id = $existing->id;
                $DB->update_record('local_spacechildpages_completions', $record);
            } else {
                // Insérer un nouveau record
                $DB->insert_record('local_spacechildpages_completions', $record);
            }
            
        } catch (\Exception $e) {
            error_log('[COMPLETION] Error logging completion: ' . $e->getMessage());
        }
    }
    
    /**
     * Calcule la progression dans un cours
     * 
     * @param \stdClass $course
     * @param int $userid
     * @return float|null Pourcentage de progression (0-100) ou null
     */
    private static function calculate_course_progress($course, $userid) {
        try {
            $percentage = \core_completion\progress::get_course_progress_percentage($course, $userid);
            return $percentage;
        } catch (\Exception $e) {
            error_log('[COMPLETION] Error calculating progress: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Envoie une notification de progression
     * 
     * @param \stdClass $user
     * @param \stdClass $course
     * @param int $percentage
     */
    private static function send_progress_notification($user, $course, $percentage) {
        $context = \context_course::instance($course->id);
        $teachers = get_enrolled_users($context, 'mod/assign:grade');
        
        $userfullname = fullname($user);
        $coursename = format_string($course->fullname);
        
        $subject = "📈 Progression : {$userfullname} - {$percentage}%";
        
        $messagetext = "L'apprenant {$userfullname} a atteint {$percentage}% de progression dans le cours \"{$coursename}\".\n\n";
        $messagetext .= "Continuez à suivre sa progression dans le rapport d'achèvement.";
        
        $messagehtml = '<p>L\'apprenant <strong>' . $userfullname . '</strong> a atteint <strong>' . $percentage . '%</strong> de progression dans le cours "<em>' . $coursename . '</em>".</p>';
        $messagehtml .= '<p>Continuez à suivre sa progression dans le rapport d\'achèvement.</p>';
        
        foreach ($teachers as $teacher) {
            $message = new \core\message\message();
            $message->component = 'local_spacechildpages';
            $message->name = 'coursecompletion';
            $message->userfrom = \core_user::get_noreply_user();
            $message->userto = $teacher;
            $message->subject = $subject;
            $message->fullmessage = $messagetext;
            $message->fullmessageformat = FORMAT_HTML;
            $message->fullmessagehtml = $messagehtml;
            $message->smallmessage = "{$userfullname} : {$percentage}% de progression";
            $message->notification = 1;
            $message->contexturl = new \moodle_url('/report/completion/user.php', [
                'course' => $course->id,
                'user' => $user->id
            ]);
            $message->contexturlname = 'Voir la progression';
            
            message_send($message);
        }
    }
    
    /**
     * Calcule le temps passé dans un cours (estimation)
     * 
     * @param int $userid
     * @param int $courseid
     * @return int|null Temps en secondes ou null
     */
    private static function calculate_time_spent($userid, $courseid) {
        global $DB;
        
        // Cette fonction nécessite le plugin logstore_standard activé
        try {
            $sql = "SELECT SUM(timecreated) as total
                    FROM {logstore_standard_log}
                    WHERE userid = :userid
                    AND courseid = :courseid
                    AND action = 'viewed'";
            
            $result = $DB->get_record_sql($sql, ['userid' => $userid, 'courseid' => $courseid]);
            
            // Estimation basique - à améliorer avec un vrai tracking
            return $result ? $result->total : null;
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Compte les activités complétées
     * 
     * @param int $userid
     * @param int $courseid
     * @return int|null Nombre d'activités ou null
     */
    private static function count_completed_activities($userid, $courseid) {
        global $DB;
        
        try {
            $sql = "SELECT COUNT(*)
                    FROM {course_modules_completion}
                    WHERE userid = :userid
                    AND coursemoduleid IN (
                        SELECT id FROM {course_modules}
                        WHERE course = :courseid
                        AND deletioninprogress = 0
                    )
                    AND completionstate > 0";
            
            return $DB->count_records_sql($sql, ['userid' => $userid, 'courseid' => $courseid]);
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Formate une durée en secondes en format lisible
     * 
     * @param int $seconds
     * @return string Durée formatée
     */
    private static function format_duration($seconds) {
        if ($seconds < 60) {
            return $seconds . ' secondes';
        } elseif ($seconds < 3600) {
            return round($seconds / 60) . ' minutes';
        } else {
            $hours = floor($seconds / 3600);
            $minutes = round(($seconds % 3600) / 60);
            return $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'min' : '');
        }
    }
}
