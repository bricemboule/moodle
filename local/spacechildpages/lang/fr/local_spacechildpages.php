<?php
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Pages Spacechild';
$string['marketingcategories'] = 'Catégories marketing';
$string['marketingcourses'] = 'Cours marketing';
$string['addcategory'] = 'Ajouter une catégorie';
$string['addcourse'] = 'Ajouter un cours';
$string['editcategory'] = 'Modifier la catégorie';
$string['editcourse'] = 'Modifier le cours';
$string['deletecategory'] = 'Supprimer';
$string['deletecourse'] = 'Supprimer';
$string['confirmdeletecategory'] = 'Supprimer la catégorie « {$a} » ?';
$string['confirmdeletecourse'] = 'Supprimer le cours « {$a} » ?';
$string['categoryname'] = 'Nom';
$string['categorymeta'] = 'Méta';
$string['categoryimage'] = 'URL de l’image';
$string['categoryurl'] = 'URL du lien';
$string['coursetitle'] = 'Nom du cours';
$string['coursedescription'] = 'Description courte';
$string['courseimage'] = 'URL de l’image';
$string['sortorder'] = 'Ordre';
$string['actions'] = 'Actions';
$string['categorysaved'] = 'Catégorie enregistrée.';
$string['categorydeleted'] = 'Catégorie supprimée.';
$string['nocategories'] = 'Aucune catégorie marketing pour le moment.';
$string['coursesaved'] = 'Cours enregistré.';
$string['coursedeleted'] = 'Cours supprimé.';
$string['nocourses'] = 'Aucun cours marketing pour le moment.';
$string['enrolrequests'] = 'Demandes d’inscription aux cours';
$string['enrolrequest:title'] = 'Demande d’inscription';
$string['enrolrequest:details'] = 'Vos informations';
$string['enrolrequest:intro'] = 'Merci de remplir le formulaire ci-dessous. Un administrateur validera votre demande avant l\'inscription.';
$string['enrolrequest:button'] = 'Envoyer la demande';
$string['enrolrequest:sent'] = 'Votre demande a été envoyée.';
$string['enrolrequest:email_subject'] = 'Nouvelle demande d’inscription';
$string['enrolrequest:email_body'] = 'Nouvelle demande d\'inscription' . "\n\n"
    . 'Nom : {$a->fullname}' . "\n"
    . 'Email : {$a->email}' . "\n"
    . 'Organisation : {$a->organisation}' . "\n"
    . 'Téléphone : {$a->phone}' . "\n"
    . 'Poste : {$a->position}' . "\n"
    . 'Cours : {$a->course}' . "\n"
    . 'Message : {$a->message}' . "\n\n"
    . 'Gérer les demandes : {$a->url}' . "\n";
$string['enrolrequest:nocourse'] = 'Aucun cours sélectionné';
$string['enrolrequest:course'] = 'Cours : {$a}';
$string['enrolrequest:date'] = 'Date';
$string['enrolrequest:course_col'] = 'Cours';
$string['enrolrequest:fullname_col'] = 'Nom complet';
$string['enrolrequest:email_col'] = 'Email';
$string['enrolrequest:organisation_col'] = 'Organisation';
$string['enrolrequest:phone_col'] = 'Téléphone';
$string['enrolrequest:position_col'] = 'Poste';
$string['enrolrequest:message_col'] = 'Message';
$string['enrolrequest:status_col'] = 'Statut';
$string['enrolrequest:status_pending'] = 'En attente';
$string['enrolrequest:status_approved'] = 'Approuvée';
$string['enrolrequest:status_rejected'] = 'Refusée';
$string['enrolrequest:approve'] = 'Approuver';
$string['enrolrequest:reject'] = 'Refuser';
$string['enrolrequest:delete'] = 'Supprimer';
$string['enrolrequest:confirmdelete'] = 'Supprimer la demande de « {$a} » ?';
$string['enrolrequest:deleted'] = 'Demande supprimée.';
$string['enrolrequest:updated'] = 'Demande mise à jour.';
$string['enrolrequest:norequests'] = 'Aucune demande pour le moment.';
$string['enrolrequest:missingtable'] = 'La table des demandes est manquante. Lancez la mise à jour du plugin.';
$string['enrolrequest:approved_enrolled'] = 'Demande approuvée et utilisateur inscrit.';
$string['enrolrequest:approved_already'] = 'Demande approuvée. Utilisateur déjà inscrit.';
$string['enrolrequest:approved_noenrol'] = 'Demande approuvée. Aucune inscription effectuée.';
$string['enrolrequest:manual_missing'] = 'Le plugin d’inscription manuelle est désactivé.';
$string['enrolrequest:manual_instance_missing'] = 'Aucune instance d’inscription manuelle active pour ce cours.';
$string['field_fullname'] = 'Nom complet';
$string['field_email'] = 'Email';
$string['field_phone'] = 'Téléphone';
$string['field_organisation'] = 'Organisation';
$string['field_position'] = 'Poste';
$string['field_message'] = 'Message';
$string['field_phone_invalid'] = 'Numéro de téléphone invalide';
