<?php
require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/governments.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les gouvernements');
$PAGE->set_heading('Pour les gouvernements');
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
        ],
        [
            'label' => 'Pour les universités',
            'url' => $universitiesurl->out(false),
        ],
        [
            'label' => 'Pour les gouvernements',
            'url' => $governmentsurl->out(false),
            'active' => true,
        ],
    ],
    'hero_title' => 'Former à grande échelle, en toute conformité.',
    'hero_subtitle' => 'Gouvernance, sécurité et indicateurs d’impact pour les institutions.',
    'page_title' => 'Pour les gouvernements',
    'hidecta' => true,
    'hero_image' => (new moodle_url('/theme/spacechild/images/gouverne.png'))->out(false),
    'hero_image_alt' => 'Illustration institutions publiques',
    'cta_primary_label' => 'Parler à un expert',
   
    'cta_secondary_label' => 'Voir le catalogue',
    'cta_secondary_url' => (new moodle_url('/course/index.php'))->out(false),
    'features' => [
        [
            'title' => 'Plateforme nationale',
            'text' => 'Multi-organisations, gouvernance et branding.',
        ],
        [
            'title' => 'Sécurité & conformité',
            'text' => 'Accès, rôles, audit et processus internes.',
        ],
        [
            'title' => 'Suivi d’impact',
            'text' => 'Statistiques, progression et indicateurs par région.',
        ],
    ],
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    'hasprograms' => true,
    'programs' => [
        [
            'tag' => 'Reskilling',
            'title' => 'Compétences numériques',
            'summary' => 'Former les agents aux outils et méthodes modernes.',
            'details' => 'Parcours modulaires • Evaluation continue',
            'cta' => 'Découvrir',
            'url' => (new moodle_url('/course/search.php', ['search' => 'numerique']))->out(false),
        ],
        [
            'tag' => 'Conformité',
            'title' => 'Sécurité & cybersécurité',
            'summary' => 'Sensibilisation et bonnes pratiques.',
            'details' => 'Quizz • Campagnes • Reporting',
            'cta' => 'Voir le programme',
            'url' => (new moodle_url('/course/search.php', ['search' => 'securite']))->out(false),
        ],
        [
            'tag' => 'Services',
            'title' => 'Amélioration du service public',
            'summary' => 'Relation usagers et qualité de service.',
            'details' => 'Rôles • Scénarios • Simulations',
            'cta' => 'Explorer',
            'url' => (new moodle_url('/course/search.php', ['search' => 'service']))->out(false),
        ],
    ],
    'hasoutcomes' => true,
    'outcomes' => [
        [
            'value' => '90%',
            'label' => 'Satisfaction des agents',
        ],
        [
            'value' => '100k+',
            'label' => 'Agents accompagnés',
        ],
        [
            'value' => '0',
            'label' => 'Non-conformité critique',
        ],
    ],
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Cadrage national',
            'text' => 'Objectifs, indicateurs et gouvernance.',
        ],
        [
            'number' => '02',
            'title' => 'Déploiement multi-entités',
            'text' => 'Parcours adaptés par région ou ministère.',
        ],
        [
            'number' => '03',
            'title' => 'Pilotage en continu',
            'text' => 'KPIs, conformité et audits.',
        ],
    ],
    'hascasestudies' => true,
    'casestudies' => [
        [
            'title' => 'Collectivité territoriale',
            'text' => 'Déploiement sur 25 directions.',
            'result' => 'Formation complétée en 6 semaines',
        ],
        [
            'title' => 'Agence nationale',
            'text' => 'Campagne cybersécurité annuelle.',
            'result' => 'Incidents réduits de 40%',
        ],
        [
            'title' => 'Ministère',
            'text' => 'Onboarding agents et process métier.',
            'result' => 'Uniformisation des pratiques',
        ],
    ],
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'L’hébergement peut-il être souverain ?',
            'answer' => 'Oui, des options d’hébergement dédiées sont possibles.',
        ],
        [
            'question' => 'La plateforme respecte-t-elle le RGPD ?',
            'answer' => 'Oui, avec gestion des consentements et audits.',
        ],
        [
            'question' => 'Peut-on segmenter par région ou entité ?',
            'answer' => 'Oui, via des espaces et rôles dédiés.',
        ],
    ],
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_spacechild/audience', $ctx);
echo $OUTPUT->footer();
