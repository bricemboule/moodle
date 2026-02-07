# 🔔 Système de notification de retard de cours - Moodle

## 📋 Vue d'ensemble

Ce système détecte automatiquement les apprenants en retard sur leurs cours et leur envoie des notifications personnalisées pour les encourager à reprendre leur formation.

---

## 🎯 Qu'est-ce qu'un "retard" ?

Un apprenant est considéré en retard si :

1. **Critère temporel** :
   - N'a pas accédé au cours depuis X jours (ex: 7 jours)
   - OU a dépassé la date limite d'une activité

2. **Critère de progression** :
   - Progression inférieure à ce qui est attendu selon la durée écoulée
   - Ex: Inscrit depuis 30 jours, progression < 30%

3. **Critère d'activité** :
   - N'a pas complété les activités obligatoires dans les délais

---

## 🏗️ Architecture du système

```
┌─────────────────────────────────────────────────────────────┐
│  TÂCHE PLANIFIÉE (Cron - tous les jours à 8h)              │
│  ↓                                                           │
│  1. Récupère tous les cours avec dates limites             │
│  2. Pour chaque cours, liste les inscrits                  │
│  3. Vérifie la dernière connexion de chaque apprenant      │
│  4. Calcule la progression attendue vs réelle              │
│  5. Identifie les apprenants en retard                     │
│  6. Envoie les notifications personnalisées                │
│  7. Log les notifications envoyées                         │
└─────────────────────────────────────────────────────────────┘
```

---

## 📁 Structure des fichiers à créer

```
/local/spacechildpages/
├── classes/
│   ├── task/
│   │   └── check_course_delays.php          ← Tâche planifiée
│   └── delay_checker.php                    ← Logique de détection
│
├── db/
│   ├── tasks.php                            ← Enregistrement cron
│   ├── messages.php                         ← Types de notifications
│   └── install.xml                          ← Table pour logs
│
└── lang/
    └── fr/
        └── local_spacechildpages.php        ← Textes des emails
```

---

## 🔧 ÉTAPE 1 : Créer la classe de détection des retards

```php
<?php
// /local/spacechildpages/classes/delay_checker.php

namespace local_spacechildpages;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/completionlib.php');

/**
 * Classe pour détecter et notifier les apprenants en retard
 * 
 * @package    local_spacechildpages
 * @copyright  2026 Qualisys
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delay_checker {
    
    /**
     * Seuil de jours sans activité avant considérer un retard
     */
    const INACTIVE_DAYS_THRESHOLD = 7;
    
    /**
     * Vérifie tous les cours et identifie les apprenants en retard
     * 
     * @return array Statistiques des notifications envoyées
     */
    public static function check_all_courses() {
        global $DB;
        
        $stats = [
            'courses_checked' => 0,
            'students_checked' => 0,
            'delays_found' => 0,
            'notifications_sent' => 0,
            'errors' => 0
        ];
        
        // Récupérer tous les cours actifs (non cachés)
        $courses = $DB->get_records('course', ['visible' => 1]);
        
        foreach ($courses as $course) {
            // Ignorer le site principal
            if ($course->id == SITEID) {
                continue;
            }
            
            $stats['courses_checked']++;
            
            try {
                $result = self::check_course_delays($course->id);
                $stats['students_checked'] += $result['students_checked'];
                $stats['delays_found'] += $result['delays_found'];
                $stats['notifications_sent'] += $result['notifications_sent'];
            } catch (\Exception $e) {
                $stats['errors']++;
                mtrace("Erreur cours {$course->id}: " . $e->getMessage());
            }
        }
        
        return $stats;
    }
    
    /**
     * Vérifie les retards pour un cours spécifique
     * 
     * @param int $courseid ID du cours
     * @return array Statistiques
     */
    public static function check_course_delays($courseid) {
        global $DB;
        
        $stats = [
            'students_checked' => 0,
            'delays_found' => 0,
            'notifications_sent' => 0
        ];
        
        // Récupérer le cours
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
        
        // Récupérer tous les étudiants inscrits
        $context = \context_course::instance($courseid);
        $students = get_enrolled_users($context, 'mod/assign:submit'); // Capacité typique étudiant
        
        foreach ($students as $student) {
            $stats['students_checked']++;
            
            // Vérifier si l'étudiant est en retard
            $delayinfo = self::check_student_delay($student->id, $courseid);
            
            if ($delayinfo['has_delay']) {
                $stats['delays_found']++;
                
                // Vérifier si on n'a pas déjà notifié récemment
                if (self::should_send_notification($student->id, $courseid)) {
                    if (self::send_delay_notification($student, $course, $delayinfo)) {
                        $stats['notifications_sent']++;
                        self::log_notification($student->id, $courseid, $delayinfo);
                    }
                }
            }
        }
        
        return $stats;
    }
    
    /**
     * Vérifie si un étudiant est en retard sur un cours
     * 
     * @param int $userid ID de l'utilisateur
     * @param int $courseid ID du cours
     * @return array Informations sur le retard
     */
    public static function check_student_delay($userid, $courseid) {
        global $DB;
        
        $result = [
            'has_delay' => false,
            'reason' => '',
            'days_inactive' => 0,
            'last_access' => null,
            'expected_progress' => 0,
            'actual_progress' => 0,
            'overdue_activities' => []
        ];
        
        // 1. VÉRIFIER LA DERNIÈRE CONNEXION AU COURS
        $lastaccess = $DB->get_field_sql(
            "SELECT timeaccess 
             FROM {user_lastaccess} 
             WHERE userid = ? AND courseid = ?",
            [$userid, $courseid]
        );
        
        if ($lastaccess) {
            $result['last_access'] = $lastaccess;
            $daysinactive = floor((time() - $lastaccess) / (24 * 60 * 60));
            $result['days_inactive'] = $daysinactive;
            
            if ($daysinactive >= self::INACTIVE_DAYS_THRESHOLD) {
                $result['has_delay'] = true;
                $result['reason'] = 'inactive';
            }
        } else {
            // Jamais connecté au cours
            $result['has_delay'] = true;
            $result['reason'] = 'never_accessed';
        }
        
        // 2. VÉRIFIER LA PROGRESSION
        $course = $DB->get_record('course', ['id' => $courseid]);
        $completion = new \completion_info($course);
        
        if ($completion->is_enabled()) {
            // Calculer la progression réelle
            $percentage = \core_completion\progress::get_course_progress_percentage($course, $userid);
            $result['actual_progress'] = $percentage ?? 0;
            
            // Calculer la progression attendue
            $enrolment = $DB->get_record_sql(
                "SELECT ue.timecreated 
                 FROM {user_enrolments} ue
                 JOIN {enrol} e ON e.id = ue.enrolid
                 WHERE ue.userid = ? AND e.courseid = ?
                 ORDER BY ue.timecreated ASC
                 LIMIT 1",
                [$userid, $courseid]
            );
            
            if ($enrolment) {
                $daysenrolled = floor((time() - $enrolment->timecreated) / (24 * 60 * 60));
                
                // Si le cours a une date de fin
                if ($course->enddate > 0) {
                    $courseduration = $course->enddate - $course->startdate;
                    $timeelapsed = time() - $course->startdate;
                    $result['expected_progress'] = min(100, ($timeelapsed / $courseduration) * 100);
                } else {
                    // Sinon, on attend ~1% par jour
                    $result['expected_progress'] = min(100, $daysenrolled);
                }
                
                // Vérifier si en retard sur la progression
                if ($result['actual_progress'] < ($result['expected_progress'] - 20)) {
                    $result['has_delay'] = true;
                    if ($result['reason'] === '') {
                        $result['reason'] = 'low_progress';
                    }
                }
            }
        }
        
        // 3. VÉRIFIER LES ACTIVITÉS EN RETARD
        $overdueactivities = self::get_overdue_activities($userid, $courseid);
        $result['overdue_activities'] = $overdueactivities;
        
        if (!empty($overdueactivities)) {
            $result['has_delay'] = true;
            if ($result['reason'] === '') {
                $result['reason'] = 'overdue_activities';
            }
        }
        
        return $result;
    }
    
    /**
     * Récupère les activités en retard pour un étudiant
     * 
     * @param int $userid
     * @param int $courseid
     * @return array
     */
    private static function get_overdue_activities($userid, $courseid) {
        global $DB;
        
        $now = time();
        $overdue = [];
        
        // Récupérer les devoirs (assignments) en retard
        $sql = "SELECT a.id, a.name, a.duedate, cm.id as cmid
                FROM {assign} a
                JOIN {course_modules} cm ON cm.instance = a.id
                JOIN {modules} m ON m.id = cm.module AND m.name = 'assign'
                WHERE a.course = :courseid
                  AND a.duedate > 0
                  AND a.duedate < :now
                  AND cm.deletioninprogress = 0
                  AND NOT EXISTS (
                      SELECT 1 FROM {assign_submission} asub
                      WHERE asub.assignment = a.id
                        AND asub.userid = :userid
                        AND asub.status = 'submitted'
                  )";
        
        $assignments = $DB->get_records_sql($sql, [
            'courseid' => $courseid,
            'now' => $now,
            'userid' => $userid
        ]);
        
        foreach ($assignments as $assign) {
            $overdue[] = [
                'type' => 'assign',
                'name' => $assign->name,
                'duedate' => $assign->duedate,
                'cmid' => $assign->cmid,
                'days_overdue' => floor(($now - $assign->duedate) / (24 * 60 * 60))
            ];
        }
        
        // Récupérer les quiz en retard
        $sql = "SELECT q.id, q.name, q.timeclose, cm.id as cmid
                FROM {quiz} q
                JOIN {course_modules} cm ON cm.instance = q.id
                JOIN {modules} m ON m.id = cm.module AND m.name = 'quiz'
                WHERE q.course = :courseid
                  AND q.timeclose > 0
                  AND q.timeclose < :now
                  AND cm.deletioninprogress = 0
                  AND NOT EXISTS (
                      SELECT 1 FROM {quiz_attempts} qa
                      WHERE qa.quiz = q.id
                        AND qa.userid = :userid
                        AND qa.state = 'finished'
                  )";
        
        $quizzes = $DB->get_records_sql($sql, [
            'courseid' => $courseid,
            'now' => $now,
            'userid' => $userid
        ]);
        
        foreach ($quizzes as $quiz) {
            $overdue[] = [
                'type' => 'quiz',
                'name' => $quiz->name,
                'duedate' => $quiz->timeclose,
                'cmid' => $quiz->cmid,
                'days_overdue' => floor(($now - $quiz->timeclose) / (24 * 60 * 60))
            ];
        }
        
        return $overdue;
    }
    
    /**
     * Vérifie si on doit envoyer une notification
     * (évite de spammer l'étudiant)
     * 
     * @param int $userid
     * @param int $courseid
     * @return bool
     */
    private static function should_send_notification($userid, $courseid) {
        global $DB;
        
        // Vérifier si une notification a été envoyée dans les 3 derniers jours
        $threedaysago = time() - (3 * 24 * 60 * 60);
        
        $recent = $DB->record_exists_select(
            'local_spacechildpages_delays',
            'userid = :userid AND courseid = :courseid AND timenotified > :since',
            [
                'userid' => $userid,
                'courseid' => $courseid,
                'since' => $threedaysago
            ]
        );
        
        return !$recent; // Envoyer seulement si pas de notification récente
    }
    
    /**
     * Envoie une notification de retard à l'étudiant
     * 
     * @param object $student
     * @param object $course
     * @param array $delayinfo
     * @return bool
     */
    private static function send_delay_notification($student, $course, $delayinfo) {
        $subject = get_string('delay_notification_subject', 'local_spacechildpages', [
            'coursename' => format_string($course->fullname)
        ]);
        
        if (strpos($subject, '[[') !== false) {
            $subject = "⏰ Rappel : Reprenez votre cours " . format_string($course->fullname);
        }
        
        // Préparer le message
        $messagetext = self::format_delay_message_text($student, $course, $delayinfo);
        $messagehtml = self::format_delay_message_html($student, $course, $delayinfo);
        
        // Créer la notification
        $message = new \core\message\message();
        $message->component = 'local_spacechildpages';
        $message->name = 'coursedelay';
        $message->userfrom = \core_user::get_noreply_user();
        $message->userto = $student;
        $message->subject = $subject;
        $message->fullmessage = $messagetext;
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = $messagehtml;
        $message->smallmessage = "Vous n'avez pas accédé à " . format_string($course->fullname) . " depuis " . $delayinfo['days_inactive'] . " jours";
        $message->notification = 1;
        $message->contexturl = new \moodle_url('/course/view.php', ['id' => $course->id]);
        $message->contexturlname = 'Accéder au cours';
        
        $messageid = message_send($message);
        
        if ($messageid) {
            mtrace("  ✓ Notification envoyée à {$student->firstname} {$student->lastname} pour le cours {$course->fullname}");
            return true;
        }
        
        return false;
    }
    
    /**
     * Formate le message texte
     */
    private static function format_delay_message_text($student, $course, $delayinfo) {
        $message = "⏰ RAPPEL DE COURS\n";
        $message .= str_repeat('=', 50) . "\n\n";
        
        $message .= "Bonjour " . fullname($student) . ",\n\n";
        
        // Message selon le type de retard
        switch ($delayinfo['reason']) {
            case 'never_accessed':
                $message .= "Vous êtes inscrit(e) au cours \"" . format_string($course->fullname) . "\" mais vous ne l'avez pas encore commencé.\n\n";
                break;
                
            case 'inactive':
                $message .= "Vous n'avez pas accédé au cours \"" . format_string($course->fullname) . "\" depuis " . $delayinfo['days_inactive'] . " jours.\n\n";
                break;
                
            case 'low_progress':
                $message .= "Votre progression dans le cours \"" . format_string($course->fullname) . "\" est inférieure à ce qui est attendu.\n\n";
                $message .= "Progression actuelle : " . round($delayinfo['actual_progress']) . "%\n";
                $message .= "Progression attendue : " . round($delayinfo['expected_progress']) . "%\n\n";
                break;
                
            case 'overdue_activities':
                $message .= "Vous avez des activités en retard dans le cours \"" . format_string($course->fullname) . "\".\n\n";
                break;
        }
        
        // Lister les activités en retard
        if (!empty($delayinfo['overdue_activities'])) {
            $message .= "Activités en retard :\n";
            foreach ($delayinfo['overdue_activities'] as $activity) {
                $message .= "  • " . $activity['name'] . " (retard de " . $activity['days_overdue'] . " jours)\n";
            }
            $message .= "\n";
        }
        
        $message .= "Nous vous encourageons à reprendre votre formation dès que possible.\n\n";
        $message .= "Accédez au cours : " . (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(false) . "\n\n";
        $message .= "Bon courage !\n";
        $message .= "L'équipe pédagogique";
        
        return $message;
    }
    
    /**
     * Formate le message HTML
     */
    private static function format_delay_message_html($student, $course, $delayinfo) {
        $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
        
        // Header
        $html .= '<div style="background: linear-gradient(135deg, #297664, #34a085); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">';
        $html .= '<h1 style="margin: 0; font-size: 24px;">⏰ Rappel de cours</h1>';
        $html .= '</div>';
        
        // Body
        $html .= '<div style="background: #f5f7f7; padding: 30px; border-radius: 0 0 10px 10px;">';
        
        $html .= '<p>Bonjour <strong>' . fullname($student) . '</strong>,</p>';
        
        // Message selon le type de retard
        $html .= '<div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #b53a3d;">';
        
        switch ($delayinfo['reason']) {
            case 'never_accessed':
                $html .= '<p>Vous êtes inscrit(e) au cours <strong>"' . format_string($course->fullname) . '"</strong> mais vous ne l\'avez pas encore commencé.</p>';
                break;
                
            case 'inactive':
                $html .= '<p>Vous n\'avez pas accédé au cours <strong>"' . format_string($course->fullname) . '"</strong> depuis <strong>' . $delayinfo['days_inactive'] . ' jours</strong>.</p>';
                break;
                
            case 'low_progress':
                $html .= '<p>Votre progression dans le cours <strong>"' . format_string($course->fullname) . '"</strong> est inférieure à ce qui est attendu.</p>';
                $html .= '<div style="margin: 15px 0;">';
                $html .= '<p style="margin: 5px 0;"><strong>Progression actuelle :</strong></p>';
                $html .= '<div style="background: #e0e0e0; border-radius: 20px; height: 30px; position: relative; overflow: hidden;">';
                $html .= '<div style="background: #f44336; width: ' . round($delayinfo['actual_progress']) . '%; height: 100%; border-radius: 20px; display: flex; align-items: center; justify-content: center;">';
                $html .= '<span style="color: white; font-weight: bold;">' . round($delayinfo['actual_progress']) . '%</span>';
                $html .= '</div></div>';
                $html .= '<p style="margin: 10px 0;"><strong>Progression attendue : ' . round($delayinfo['expected_progress']) . '%</strong></p>';
                $html .= '</div>';
                break;
                
            case 'overdue_activities':
                $html .= '<p>Vous avez des activités en retard dans le cours <strong>"' . format_string($course->fullname) . '"</strong>.</p>';
                break;
        }
        
        $html .= '</div>';
        
        // Activités en retard
        if (!empty($delayinfo['overdue_activities'])) {
            $html .= '<div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">';
            $html .= '<h3 style="margin-top: 0; color: #b53a3d;">Activités en retard :</h3>';
            $html .= '<ul style="list-style: none; padding: 0;">';
            
            foreach ($delayinfo['overdue_activities'] as $activity) {
                $html .= '<li style="padding: 10px; margin: 5px 0; background: #fff5f5; border-left: 3px solid #b53a3d;">';
                $html .= '📌 <strong>' . $activity['name'] . '</strong><br>';
                $html .= '<small style="color: #666;">Retard de ' . $activity['days_overdue'] . ' jour(s)</small>';
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            $html .= '</div>';
        }
        
        // CTA
        $courseurl = new \moodle_url('/course/view.php', ['id' => $course->id]);
        $html .= '<div style="text-align: center; margin: 30px 0;">';
        $html .= '<a href="' . $courseurl->out(false) . '" style="display: inline-block; background: #b53a3d; color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; font-weight: bold;">📚 Reprendre le cours</a>';
        $html .= '</div>';
        
        $html .= '<p style="text-align: center; color: #666;">Nous vous encourageons à reprendre votre formation dès que possible.</p>';
        $html .= '<p style="text-align: center; color: #666; font-size: 12px;">Bon courage !<br>L\'équipe pédagogique</p>';
        
        $html .= '</div>'; // Fin body
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Enregistre la notification envoyée
     */
    private static function log_notification($userid, $courseid, $delayinfo) {
        global $DB;
        
        $record = new \stdClass();
        $record->userid = $userid;
        $record->courseid = $courseid;
        $record->reason = $delayinfo['reason'];
        $record->days_inactive = $delayinfo['days_inactive'];
        $record->progress = $delayinfo['actual_progress'];
        $record->timenotified = time();
        
        $DB->insert_record('local_spacechildpages_delays', $record);
    }
}
```

Cette classe est complète et prête à l'emploi. Voulez-vous que je continue avec les autres fichiers (tâche planifiée, configuration, etc.) ?
