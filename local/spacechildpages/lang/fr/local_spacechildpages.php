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
$string['categoryimage'] = 'Image';
$string['categoryimageurl'] = 'URL de l’image (optionnelle)';
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
$string['enrolrequest:existing_question'] = 'Êtes-vous déjà inscrit(e) à un cours ?';
$string['enrolrequest:existing_yes'] = 'Oui, je suis déjà inscrit(e)';
$string['enrolrequest:existing_no'] = 'Non, première inscription';
$string['enrolrequest:existing_note'] = 'Vos informations de profil seront utilisées pour cette demande.';
$string['enrolrequest:existing_email_notfound'] = 'Adresse email inconnue. Merci de choisir "Non, première inscription" pour remplir le formulaire complet.';
$string['enrolrequest:existing_email_duplicate'] = 'Plusieurs comptes utilisent cette adresse email. Merci de contacter le support.';
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
$string['enrolrequest:norequests_filtered'] = 'Aucune demande ne correspond aux filtres.';
$string['enrolrequest:missingtable'] = 'La table des demandes est manquante. Lancez la mise à jour du plugin.';
$string['enrolrequest:approved_enrolled'] = 'Demande approuvée et utilisateur inscrit.';
$string['enrolrequest:approved_already'] = 'Demande approuvée. Utilisateur déjà inscrit.';
$string['enrolrequest:approved_noenrol'] = 'Demande approuvée. Aucune inscription effectuée.';
$string['enrolrequest:approved_subject'] = 'Votre demande d’inscription sur {$a} a été approuvée';
$string['enrolrequest:approved_body'] = 'Bonjour {$a->fullname},' . "\n\n"
    . 'Votre demande d\'inscription a été approuvée.' . "\n\n"
    . 'Cours : {$a->course}' . "\n"
    . 'Accès au cours : {$a->courseurl}' . "\n\n"
    . 'Informations de connexion :' . "\n"
    . 'URL de connexion : {$a->loginurl}' . "\n"
    . 'Identifiant : {$a->username}' . "\n"
    . 'Email : {$a->email}' . "\n\n"
    . 'Mot de passe : si vous en avez déjà un, utilisez-le. Sinon, consultez l\'email de création/réinitialisation.' . "\n"
    . 'Mot de passe oublié ? {$a->forgoturl}' . "\n\n"
    . 'Merci,' . "\n"
    . '{$a->sitename}' . "\n";
$string['enrolrequest:user_notfound'] = 'Aucun utilisateur trouvé pour cet email : {$a}.';
$string['enrolrequest:manual_missing'] = 'Le plugin d’inscription manuelle est désactivé.';
$string['enrolrequest:manual_instance_missing'] = 'Aucune instance d’inscription manuelle active pour ce cours.';
$string['enrolrequest:filter_status'] = 'Statut';
$string['enrolrequest:filter_status_all'] = 'Tous les statuts';
$string['enrolrequest:filter_course'] = 'Cours';
$string['enrolrequest:filter_course_all'] = 'Tous les cours';
$string['enrolrequest:filter_search'] = 'Recherche';
$string['enrolrequest:filter_search_placeholder'] = 'Nom ou email';
$string['enrolrequest:filter_apply'] = 'Filtrer';
$string['enrolrequest:filter_reset'] = 'Réinitialiser';
$string['enrolrequest:progress_link'] = 'Voir la progression';
$string['field_fullname'] = 'Nom complet';
$string['field_email'] = 'Email';
$string['field_course'] = 'Cours';
$string['field_course_select'] = 'Sélectionner un cours';
$string['field_course_required'] = 'Veuillez sélectionner un cours.';
$string['field_phone'] = 'Téléphone';
$string['field_organisation'] = 'Organisation';
$string['field_position'] = 'Poste';
$string['field_message'] = 'Message';
$string['field_phone_invalid'] = 'Numéro de téléphone invalide';
$string['course_detail_category'] = 'Catégorie';
$string['course_detail_teachers'] = 'Enseignants';
$string['course_detail_start'] = 'Début';
$string['course_detail_end'] = 'Fin';
$string['course_detail_duration'] = 'Durée';
$string['course_detail_activities'] = 'Nombre d\'activités';
$string['progress:dashboard'] = 'Tableau de suivi';
$string['progress:dashboard_title'] = 'Suivi des progressions et achèvements';
$string['progress:filter_course'] = 'Cours';
$string['progress:filter_course_all'] = 'Tous les cours';
$string['progress:filter_userid'] = 'ID utilisateur';
$string['progress:filter_email'] = 'Email';
$string['progress:filter_email_placeholder'] = 'utilisateur@exemple.com';
$string['progress:filter_apply'] = 'Filtrer';
$string['progress:filter_reset'] = 'Réinitialiser';
$string['progress:email_invalid'] = 'Veuillez saisir une adresse email valide.';
$string['progress:email_notfound'] = 'Aucun compte trouvé pour cet email.';
$string['progress:email_duplicate'] = 'Plusieurs comptes utilisent cet email. Utilisez l’ID utilisateur.';
$string['progress:user_notfound'] = 'Utilisateur introuvable.';
$string['progress:missingtable'] = 'Les tables de suivi sont manquantes. Lancez la mise à jour du plugin.';
$string['progress:overview_title'] = 'Suivi d’achèvement du cours';
$string['progress:overview_select_course'] = 'Sélectionnez un cours pour afficher les apprenants et leur progression.';
$string['progress:overview_empty'] = 'Aucun apprenant trouvé pour ce cours.';
$string['progress:completions_title'] = 'Achèvements de cours';
$string['progress:progress_title'] = 'Jalons de progression';
$string['progress:completions_empty'] = 'Aucun achèvement trouvé.';
$string['progress:progress_empty'] = 'Aucun jalon de progression trouvé.';
$string['progress:col_index'] = 'N°';
$string['progress:col_date'] = 'Date';
$string['progress:col_course'] = 'Cours';
$string['progress:col_user'] = 'Apprenant';
$string['progress:col_email'] = 'Email';
$string['progress:col_completion'] = 'Pourcentage d’achèvement';
$string['progress:col_grade'] = 'Note';
$string['progress:col_notified'] = 'Notification';
$string['progress:col_milestone'] = 'Jalon';
$string['progress:view_report'] = 'Voir le rapport';
