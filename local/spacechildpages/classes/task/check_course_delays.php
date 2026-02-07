<?php
// /local/spacechildpages/classes/task/check_course_delays.php

namespace local_spacechildpages\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/spacechildpages/classes/delay_checker.php');

/**
 * Tâche planifiée pour vérifier les retards de cours
 * 
 * S'exécute tous les jours pour identifier les apprenants en retard
 * et leur envoyer des notifications de rappel.
 *
 * @package    local_spacechildpages
 * @copyright  2026 Qualisys
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class check_course_delays extends \core\task\scheduled_task {
    
    /**
     * Retourne le nom de la tâche
     * 
     * @return string
     */
    public function get_name() {
        return get_string('task_check_delays', 'local_spacechildpages');
    }
    
    /**
     * Exécute la tâche
     */
    public function execute() {
        mtrace('');
        mtrace('════════════════════════════════════════════════════════');
        mtrace('🔔 VÉRIFICATION DES RETARDS DE COURS');
        mtrace('════════════════════════════════════════════════════════');
        mtrace('');
        
        $starttime = time();
        
        try {
            // Exécuter la vérification de tous les cours
            $stats = \local_spacechildpages\delay_checker::check_all_courses();
            
            // Afficher les statistiques
            mtrace('');
            mtrace('────────────────────────────────────────────────────────');
            mtrace('📊 RÉSULTATS');
            mtrace('────────────────────────────────────────────────────────');
            mtrace('Cours vérifiés       : ' . $stats['courses_checked']);
            mtrace('Étudiants vérifiés   : ' . $stats['students_checked']);
            mtrace('Retards détectés     : ' . $stats['delays_found']);
            mtrace('Notifications envoyées : ' . $stats['notifications_sent']);
            
            if ($stats['errors'] > 0) {
                mtrace('⚠️  Erreurs rencontrées : ' . $stats['errors']);
            }
            
            $duration = time() - $starttime;
            mtrace('');
            mtrace('⏱️  Durée d\'exécution : ' . $duration . ' secondes');
            mtrace('');
            mtrace('════════════════════════════════════════════════════════');
            mtrace('✅ Vérification terminée avec succès');
            mtrace('════════════════════════════════════════════════════════');
            mtrace('');
            
        } catch (\Exception $e) {
            mtrace('');
            mtrace('════════════════════════════════════════════════════════');
            mtrace('❌ ERREUR CRITIQUE');
            mtrace('════════════════════════════════════════════════════════');
            mtrace('Message : ' . $e->getMessage());
            mtrace('Trace : ' . $e->getTraceAsString());
            mtrace('════════════════════════════════════════════════════════');
            mtrace('');
            
            throw $e;
        }
    }
}
