<?php
// /local/spacechildpages/db/messages.php

/**
 * Définition des fournisseurs de messages pour le plugin local_spacechildpages
 * 
 * Ce fichier déclare les types de notifications que le plugin peut envoyer.
 * Les utilisateurs peuvent configurer comment ils souhaitent recevoir ces notifications
 * (email, popup, mobile, etc.) dans leurs préférences de messagerie.
 *
 * @package    local_spacechildpages
 * @copyright  2026 Qualisys
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$messageproviders = [
    
    /**
     * Notifications d'achèvement de cours
     * 
     * Envoyées aux enseignants et administrateurs quand un apprenant
     * termine un cours ou atteint un jalon de progression.
     */
    'coursecompletion' => [
        // Capacité requise pour recevoir ces notifications
        // Les utilisateurs doivent avoir cette capacité dans le contexte du cours
        'capability' => 'local/spacechildpages:receivecompletionnotifications',
        
        // Configuration par défaut des canaux de notification
        'defaults' => [
            // Popup = notification dans Moodle (cloche en haut à droite)
            'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
            
            // Email
            'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
            
            // Mobile (application Moodle Mobile)
            // 'mobile' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
        ],
    ],
    
    /**
     * Rapports hebdomadaires d'achèvement
     * 
     * Envoyés uniquement aux administrateurs, résumant les achèvements
     * de la semaine écoulée.
     */
    'weeklyreport' => [
        'capability' => 'local/spacechildpages:receiveweeklyreports',
        'defaults' => [
            'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
        ],
    ],
    
    /**
     * Notifications de demandes d'inscription (enrol requests)
     * 
     * Envoyées aux administrateurs quand quelqu'un demande à s'inscrire
     * via le formulaire public.
     */
    'enrolrequest' => [
        'capability' => 'local/spacechildpages:receiveenrolrequests',
        'defaults' => [
            'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
            'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
        ],
    ],
];

/**
 * EXPLICATION DES CONSTANTES :
 * 
 * MESSAGE_PERMITTED          = L'utilisateur peut activer/désactiver ce canal
 * MESSAGE_FORCED             = L'utilisateur ne peut pas désactiver ce canal
 * MESSAGE_DISALLOWED         = Ce canal est désactivé et ne peut pas être activé
 * 
 * MESSAGE_DEFAULT_ENABLED    = Activé par défaut
 * 
 * EXEMPLES :
 * 
 * 1. Toujours envoyer par email, non configurable par l'utilisateur :
 *    'email' => MESSAGE_FORCED
 * 
 * 2. Permettre l'email mais désactivé par défaut :
 *    'email' => MESSAGE_PERMITTED
 * 
 * 3. Email activé par défaut, utilisateur peut le désactiver :
 *    'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED
 * 
 * 4. Popup activé par défaut :
 *    'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED
 */
