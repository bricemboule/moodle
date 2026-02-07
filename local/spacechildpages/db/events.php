<?php
// /local/spacechildpages/db/events.php

/**
 * Définition des observateurs d'événements pour le plugin local_spacechildpages
 * 
 * Ce fichier enregistre les fonctions callback qui seront appelées lorsque
 * certains événements Moodle se produisent (comme l'achèvement d'un cours).
 *
 * @package    local_spacechildpages
 * @copyright  2026 Qualisys
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    
    /**
     * Observateur pour l'achèvement complet d'un cours
     * 
     * Déclenché quand un apprenant complète tous les critères d'achèvement.
     * Événement natif Moodle : \core\event\course_completed
     */
    [
        'eventname' => '\core\event\course_completed',
        'callback' => 'local_spacechildpages_observer::course_completed',
        'includefile' => null, // Autoload via classes/observer.php
        'priority' => 0,
        'internal' => true,
    ],
    
    /**
     * Observateur pour la mise à jour de l'achèvement d'une activité
     * 
     * Déclenché quand une activité (module de cours) est marquée comme complétée.
     * Permet de suivre la progression et notifier aux jalons (25%, 50%, 75%).
     * Événement natif Moodle : \core\event\course_module_completion_updated
     */
    [
        'eventname' => '\core\event\course_module_completion_updated',
        'callback' => 'local_spacechildpages_observer::module_completion_updated',
        'includefile' => null,
        'priority' => 0,
        'internal' => true,
    ],
    
    /**
     * Observateur pour la création d'une note
     * 
     * Optionnel : permet de notifier quand une nouvelle note est attribuée.
     * Événement natif Moodle : \core\event\user_graded
     */
    /*
    [
        'eventname' => '\core\event\user_graded',
        'callback' => 'local_spacechildpages_observer::user_graded',
        'includefile' => null,
        'priority' => 0,
        'internal' => true,
    ],
    */
    
    /**
     * Observateur pour l'inscription d'un utilisateur à un cours
     * 
     * Optionnel : permet de notifier les enseignants quand un nouvel apprenant rejoint.
     * Événement natif Moodle : \core\event\user_enrolment_created
     */
    /*
    [
        'eventname' => '\core\event\user_enrolment_created',
        'callback' => 'local_spacechildpages_observer::user_enrolled',
        'includefile' => null,
        'priority' => 0,
        'internal' => true,
    ],
    */
];
