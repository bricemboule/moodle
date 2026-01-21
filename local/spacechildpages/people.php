<?php
require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/people.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les personnes');
$PAGE->set_heading('Pour les personnes');
$PAGE->requires->js(new moodle_url('/theme/spacechild/javascript/marketing.js'));

$peopleurl = new moodle_url('/local/spacechildpages/people.php');

$universitiesurl = new moodle_url('/local/spacechildpages/universities.php');
$governmentsurl = new moodle_url('/local/spacechildpages/governments.php');
$loginurl = new moodle_url('/login/index.php');
$signupurl = new moodle_url('/local/spacechildpages/enrol_request.php');
$supporturl = new moodle_url('/user/contactsitesupport.php');
$sitename = format_string($SITE->shortname ?: $SITE->fullname);

$marketingcategories = [];
if (class_exists('\\local_spacechildpages\\marketing_categories')) {
    $marketingcategories = \local_spacechildpages\marketing_categories::get_categories(8);
}
$marketingcourses = [];
if (class_exists('\\local_spacechildpages\\marketing_courses')) {
    $marketingcourses = \local_spacechildpages\marketing_courses::get_courses(8);
}

$ctx = [
    'config' => [
        'wwwroot' => $CFG->wwwroot,
    ],
    'wwwroot' => $CFG->wwwroot,
    'sitename' => $sitename,
    'loginurl' => $loginurl->out(false),
    'signupurl' => $signupurl->out(false),
    'supporturl' => $supporturl->out(false),
    'currentyear' => date('Y'),
    'peopleurl' => $peopleurl->out(false),
  
    'universitiesurl' => $universitiesurl->out(false),
    'governmentsurl' => $governmentsurl->out(false),
    'hastoplinks' => true,
    'toplinks' => [
        [
            'label' => 'Pour les personnes',
            'url' => $peopleurl->out(false),
            'active' => true,
        ],
        [
            'label' => 'Pour les universités',
            'url' => $universitiesurl->out(false),
        ],
    
        [
            'label' => 'Pour les gouvernements',
            'url' => $governmentsurl->out(false),
        ],
    ],
    'hero_title' => 'Des compétences utiles, à votre rythme.',
    'hero_subtitle' => 'Parcours guidés, projets pratiques et certifications pour booster votre avenir.',
    'page_title' => 'Pour les personnes',
    'hidecta' => true,
    'hero_image' => (new moodle_url('/theme/spacechild/images/personne.png'))->out(false),
    'hero_image_alt' => 'Illustration apprentissage individuel',
    'cta_primary_label' => 'Explorer les cours',
    'cta_primary_url' => (new moodle_url('/course/search.php'))->out(false),
    'cta_secondary_label' => 'Créer un compte',
    'cta_secondary_url' => $signupurl->out(false),
    'features' => [
        [
            'title' => 'Parcours guidés',
            'text' => 'Progressez étape par étape avec des objectifs clairs.',
        ],
        [
            'title' => 'Certifications',
            'text' => 'Valorisez vos compétences avec des attestations reconnues.',
        ],
        [
            'title' => 'Projets pratiques',
            'text' => 'Construisez un portfolio avec des cas concrets.',
        ],
    ],
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    'hasprograms' => true,
    'programs' => [
        [
            'tag' => 'Carrière',
            'title' => 'Data & IA pour débutants',
            'summary' => 'Comprendre les bases et réaliser vos premiers projets.',
            'details' => '6 cours • 8 semaines • 3 projets',
            'cta' => 'Voir le parcours',
            'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
        ],
        [
            'tag' => 'Business',
            'title' => 'Gestion de projet moderne',
            'summary' => 'Agilité, planification et pilotage par objectifs.',
            'details' => '5 cours • 6 semaines • 2 projets',
            'cta' => 'Découvrir',
            'url' => (new moodle_url('/course/search.php', ['search' => 'gestion']))->out(false),
        ],
        [
            'tag' => 'Créatif',
            'title' => 'UX/UI Design',
            'summary' => 'Concevez des expériences utiles et accessibles.',
            'details' => '7 cours • 7 semaines • 4 projets',
            'cta' => 'Explorer',
            'url' => (new moodle_url('/course/search.php', ['search' => 'design']))->out(false),
        ],
    ],
    'hasoutcomes' => true,
    'outcomes' => [
        [
            'value' => '95%',
            'label' => 'Satisfaction apprenants',
        ],
        [
            'value' => '120h',
            'label' => 'Contenu disponible',
        ],
        [
            'value' => '3x',
            'label' => 'Plus de projets pratiques',
        ],
    ],
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Choisissez votre parcours',
            'text' => 'Sélectionnez un objectif clair selon votre niveau.',
        ],
        [
            'number' => '02',
            'title' => 'Apprenez par la pratique',
            'text' => 'Cours courts, exercices et projets guidés.',
        ],
        [
            'number' => '03',
            'title' => 'Validez vos acquis',
            'text' => 'Obtenez un certificat et enrichissez votre CV.',
        ],
    ],
    'hascasestudies' => true,
    'casestudies' => [
        [
            'title' => 'Reconversion en 12 semaines',
            'text' => 'Accompagnement, projets et coaching léger.',
            'result' => 'Portfolio prêt pour les entretiens',
        ],
        [
            'title' => 'Montée en compétences rapide',
            'text' => 'Modules courts adaptés aux temps libres.',
            'result' => 'Compétences appliquées en poste',
        ],
        [
            'title' => 'Préparation à une certification',
            'text' => 'Quizz, révisions et examens blancs.',
            'result' => 'Certification obtenue',
        ],
    ],
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'Puis-je suivre les cours à mon rythme ?',
            'answer' => 'Oui, les modules sont accessibles 24/7 et progressifs.',
        ],
        [
            'question' => 'Ai-je besoin de prérequis ?',
            'answer' => 'Les parcours débutants sont conçus pour démarrer de zéro.',
        ],
        [
            'question' => 'Les certificats sont-ils reconnus ?',
            'answer' => 'Ils attestent de compétences pratiques avec projets réalisés.',
        ],
    ],
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_spacechild/audience', $ctx);
echo $OUTPUT->footer();
