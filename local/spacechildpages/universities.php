<?php
require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/universities.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les universités');
$PAGE->set_heading('Pour les universités');
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
            'active' => true,
        ],
        [
            'label' => 'Pour les gouvernements',
            'url' => $governmentsurl->out(false),
        ],
    ],
    'hero_title' => 'Des parcours clairs pour vos étudiants.',
    'hero_subtitle' => 'Branding, suivi et catalogue structuré pour moderniser votre campus.',
    'page_title' => 'Pour les universités',
    'hidecta' => true,
    'hero_image' => (new moodle_url('/theme/spacechild/images/universite.jpeg'))->out(false),
    'hero_image_alt' => 'Illustration universités et campus',
    'cta_primary_label' => 'Demander une démo',
   
    'cta_secondary_label' => 'Voir le catalogue',
    'cta_secondary_url' => (new moodle_url('/course/index.php'))->out(false),
    'features' => [
        [
            'title' => 'Branding',
            'text' => 'Une expérience à votre image pour vos publics.',
        ],
        [
            'title' => 'Suivi & reporting',
            'text' => 'Assiduité, progression et tableaux de bord.',
        ],
        [
            'title' => 'Catalogue structuré',
            'text' => 'Recherche, filtres et organisation par compétences.',
        ],
    ],
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    'hasprograms' => true,
    'programs' => [
        [
            'tag' => 'Hybride',
            'title' => 'Parcours blended learning',
            'summary' => 'Présentiel + e-learning pour plus d’impact.',
            'details' => 'Modules flexibles • Suivi enseignant',
            'cta' => 'Découvrir',
            'url' => (new moodle_url('/course/index.php'))->out(false),
        ],
        [
            'tag' => 'Diplômant',
            'title' => 'Micro-credentials',
            'summary' => 'Badges et certificats reconnus.',
            'details' => 'Évaluations • Rôles personnalisés',
            'cta' => 'Voir les options',
            'url' => (new moodle_url('/course/index.php'))->out(false),
        ],
        [
            'tag' => 'Campus',
            'title' => 'Parcours de rentrée',
            'summary' => 'Onboarding étudiants et ressources clés.',
            'details' => 'Guides • Quizz • Suivi',
            'cta' => 'Explorer',
            'url' => (new moodle_url('/course/index.php'))->out(false),
        ],
    ],
    'hasoutcomes' => true,
    'outcomes' => [
        [
            'value' => '+22%',
            'label' => 'Taux de complétion',
        ],
        [
            'value' => '4.6/5',
            'label' => 'Satisfaction étudiante',
        ],
        [
            'value' => '30k+',
            'label' => 'Étudiants accompagnés',
        ],
    ],
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Cadrage pédagogique',
            'text' => 'Objectifs, compétences et modalités d’évaluation.',
        ],
        [
            'number' => '02',
            'title' => 'Co-création des contenus',
            'text' => 'Ressources, quizz et activités guidées.',
        ],
        [
            'number' => '03',
            'title' => 'Déploiement & analytics',
            'text' => 'Suivi de cohortes et reporting.',
        ],
    ],
    'hascasestudies' => true,
    'casestudies' => [
        [
            'title' => 'Université régionale',
            'text' => 'Blended learning sur 12 formations.',
            'result' => 'Engagement +25%',
        ],
        [
            'title' => 'École d’ingénieurs',
            'text' => 'Micro-credentials pour 4 spécialités.',
            'result' => 'Insertion pro accélérée',
        ],
        [
            'title' => 'Campus santé',
            'text' => 'Parcours d’onboarding des externes.',
            'result' => 'Temps d’intégration réduit',
        ],
    ],
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'Peut-on intégrer vos contenus au LMS existant ?',
            'answer' => 'Oui, nous proposons des intégrations et du SCORM/H5P.',
        ],
        [
            'question' => 'Existe-t-il des dashboards pour les enseignants ?',
            'answer' => 'Des tableaux de bord dédiés sont disponibles.',
        ],
        [
            'question' => 'Comment personnaliser l’expérience ?',
            'answer' => 'Branding, navigation et parcours sont configurables.',
        ],
    ],
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_spacechild/audience', $ctx);
echo $OUTPUT->footer();
