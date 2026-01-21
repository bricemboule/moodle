<?php
defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $OUTPUT, $SITE;

$PAGE->requires->css(new moodle_url('/theme/spacechild/style/marketing.css'));
$PAGE->requires->js(new moodle_url('/theme/spacechild/javascript/marketing.js'));

$marketingcategories = [];
if (class_exists('\\local_spacechildpages\\marketing_categories')) {
    $marketingcategories = \local_spacechildpages\marketing_categories::get_categories(8);
}
$marketingcourses = [];
if (class_exists('\\local_spacechildpages\\marketing_courses')) {
    $marketingcourses = \local_spacechildpages\marketing_courses::get_courses(8);
}

$templatecontext = [
    'wwwroot' => $CFG->wwwroot,
    'sitename' => format_string($SITE->shortname ?: $SITE->fullname),
    'currentyear' => date('Y'),

    'loginurl' => (new moodle_url('/login/index.php'))->out(false),
    'signupurl' => (new moodle_url('/local/spacechildpages/enrol_request.php'))->out(false),

    'supporturl' => (new moodle_url('/user/contactsitesupport.php'))->out(false),

    'peopleurl' => (new moodle_url('/local/spacechildpages/people.php'))->out(false),
   
    'universitiesurl' => (new moodle_url('/local/spacechildpages/universities.php'))->out(false),
    'governmentsurl' => (new moodle_url('/local/spacechildpages/governments.php'))->out(false),
    'featuredprogram' => [
        'tag' => 'Parcours certifiant',
        'title' => 'Management de la qualité ISO 9001',
        'summary' => 'Mettre en place un SMQ complet, de la documentation aux audits internes.',
        'duration' => '6 semaines',
        'level' => 'Débutant',
        'projects' => '4 ateliers',
        'cta' => 'Voir le parcours',
        'url' => (new moodle_url('/course/search.php', ['search' => 'iso 9001']))->out(false),
    ],
    'hasgoals' => true,
    'goals' => [
        [
            'label' => 'Carrière',
            'title' => 'Changer de carrière',
            'text' => 'Des parcours guidés pour décrocher un nouveau poste.',
            'cta' => 'Voir les parcours',
            'url' => (new moodle_url('/local/spacechildpages/people.php'))->out(false),
        ],
        [
            'label' => 'Compétences',
            'title' => 'Monter en compétences',
            'text' => 'Cours courts, labs et micro-certifications ciblées.',
            'cta' => 'Explorer',
            'url' => (new moodle_url('/course/search.php'))->out(false),
        ],
        [
            'label' => 'Certification',
            'title' => 'Valider vos acquis',
            'text' => 'Badges et attestations à partager sur votre profil.',
            'cta' => 'Découvrir',
            'url' => (new moodle_url('/course/search.php', ['search' => 'certificat']))->out(false),
        ],
        [
            'label' => 'Équipes',
            'title' => 'Former une équipe',
            'text' => 'Pilotage, reporting et accompagnement dédiés.',
            'cta' => 'Pour les entreprises',
            'url' => (new moodle_url('/local/spacechildpages/business.php'))->out(false),
        ],
    ],
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    'hasprograms' => true,
    'programs' => [
        [
            'tag' => 'Certifiant',
            'partner' => 'Qualisys Consulting',
            'title' => 'Manager Qualité ISO 9001',
            'summary' => 'Structurer un SMQ complet et piloter la conformité.',
            'duration' => '8 semaines',
            'level' => 'Débutant',
            'projects' => '5 projets',
            'cta' => 'Voir le programme',
            'url' => (new moodle_url('/course/search.php', ['search' => 'iso 9001']))->out(false),
        ],
        [
            'tag' => 'HSE',
            'partner' => 'Qualisys Consulting',
            'title' => 'Responsable HSE',
            'summary' => 'Prévention des risques, conformité et culture sécurité.',
            'duration' => '10 semaines',
            'level' => 'Intermédiaire',
            'projects' => '4 cas pratiques',
            'cta' => 'Découvrir',
            'url' => (new moodle_url('/course/search.php', ['search' => 'hse']))->out(false),
        ],
        [
            'tag' => 'Performance',
            'partner' => 'Qualisys Consulting',
            'title' => 'Excellence opérationnelle',
            'summary' => 'Lean, KPI et amélioration continue.',
            'duration' => '6 semaines',
            'level' => 'Débutant',
            'projects' => 'Labs guidés',
            'cta' => 'Explorer',
            'url' => (new moodle_url('/course/search.php', ['search' => 'lean']))->out(false),
        ],
    ],
    'haskpis' => true,
    'kpis' => [
        [
            'value' => '92%',
            'label' => 'Satisfaction apprenants',
        ],
        [
            'value' => '4.8/5',
            'label' => 'Note moyenne',
        ],
        [
            'value' => '78%',
            'label' => 'Taux de complétion',
        ],
    ],
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Choisir un parcours',
            'text' => 'Des objectifs clairs selon votre niveau.',
        ],
        [
            'number' => '02',
            'title' => 'Apprendre par la pratique',
            'text' => 'Cours courts, projets et feedback.',
        ],
        [
            'number' => '03',
            'title' => 'Certifier vos acquis',
            'text' => 'Attestation finale et portfolio.',
        ],
    ],
    'hastestimonials' => true,
    'testimonials' => [
        [
            'quote' => 'Le programme est structuré comme un vrai bootcamp.',
            'name' => 'Nadia R.',
            'role' => 'Analyste Data',
        ],
        [
            'quote' => 'Des exercices concrets qui m’ont fait progresser vite.',
            'name' => 'Julien M.',
            'role' => 'Chef de projet',
        ],
        [
            'quote' => 'Support réactif et contenus à jour.',
            'name' => 'Leila T.',
            'role' => 'Product Manager',
        ],
    ],
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'Dois-je suivre un horaire fixe ?',
            'answer' => 'Non, tout est disponible en autonomie.',
        ],
        [
            'question' => 'Y a-t-il des projets pratiques ?',
            'answer' => 'Oui, chaque programme inclut des projets guidés.',
        ],
        [
            'question' => 'Puis-je obtenir un certificat ?',
            'answer' => 'Une attestation est délivrée à la fin du parcours.',
        ],
    ],
];

echo $OUTPUT->header();

if (!isloggedin() || isguestuser()) {
    // ✅ IMPORTANT : component = theme_spacechild
    echo $OUTPUT->render_from_template('theme_spacechild/spacechild/landing', $templatecontext);
} else {
    echo '<div class="container-fluid">';
    echo $OUTPUT->main_content();
    echo '</div>';
}

echo $OUTPUT->footer();
