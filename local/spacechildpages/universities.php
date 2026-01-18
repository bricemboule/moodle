<?php
require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/universities.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les universités');
$PAGE->set_heading('Pour les universités');

$peopleurl = new moodle_url('/local/spacechildpages/people.php');

$universitiesurl = new moodle_url('/local/spacechildpages/universities.php');
$governmentsurl = new moodle_url('/local/spacechildpages/governments.php');
$loginurl = new moodle_url('/login/index.php');
$signupurl = new moodle_url('/login/signup.php');
$supporturl = new moodle_url('/user/contactsitesupport.php');
$sitename = format_string($SITE->shortname ?: $SITE->fullname);

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
    'hascategories' => true,
    'categories' => [
        [
            'name' => 'Informatique',
            'meta' => 'Développement & systèmes',
            'url' => (new moodle_url('/course/search.php', ['search' => 'informatique']))->out(false),
        ],
        [
            'name' => 'Sciences & Data',
            'meta' => 'Analyse & statistiques',
            'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
        ],
        [
            'name' => 'Management',
            'meta' => 'Stratégie & leadership',
            'url' => (new moodle_url('/course/search.php', ['search' => 'management']))->out(false),
        ],
        [
            'name' => 'Santé',
            'meta' => 'Parcours cliniques',
            'url' => (new moodle_url('/course/search.php', ['search' => 'sante']))->out(false),
        ],
        [
            'name' => 'Langues',
            'meta' => 'Certifications',
            'url' => (new moodle_url('/course/search.php', ['search' => 'langues']))->out(false),
        ],
        [
            'name' => 'Pédagogie',
            'meta' => 'Innovation éducative',
            'url' => (new moodle_url('/course/search.php', ['search' => 'pedagogie']))->out(false),
        ],
        [
            'name' => 'Recherche',
            'meta' => 'Méthodes & outils',
            'url' => (new moodle_url('/course/search.php', ['search' => 'recherche']))->out(false),
        ],
        [
            'name' => 'Entrepreneuriat',
            'meta' => 'Incubation & projets',
            'url' => (new moodle_url('/course/search.php', ['search' => 'entrepreneuriat']))->out(false),
        ],
    ],
    'hascourses' => true,
    'courses' => [
        [
            'title' => 'Initiation au Data Science',
            'summary' => 'Probabilités, Python et visualisation.',
            'cta' => 'Voir le cours →',
            'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
        ],
        [
            'title' => 'Méthodologie de recherche',
            'summary' => 'Protocoles, bibliographie et rédaction.',
            'cta' => 'Voir le cours →',
            'url' => (new moodle_url('/course/search.php', ['search' => 'recherche']))->out(false),
        ],
        [
            'title' => 'Programmation Web',
            'summary' => 'HTML, CSS, JavaScript et projets.',
            'cta' => 'Voir le cours →',
            'url' => (new moodle_url('/course/search.php', ['search' => 'web']))->out(false),
        ],
        [
            'title' => 'Pédagogie active',
            'summary' => 'Apprentissage par projets et évaluation.',
            'cta' => 'Voir le cours →',
            'url' => (new moodle_url('/course/search.php', ['search' => 'pedagogie']))->out(false),
        ],
        [
            'title' => 'Gestion de projet universitaire',
            'summary' => 'Planification et coordination d’équipes.',
            'cta' => 'Voir le cours →',
            'url' => (new moodle_url('/course/search.php', ['search' => 'projet']))->out(false),
        ],
        [
            'title' => 'Anglais académique',
            'summary' => 'Présentations, publications et échanges.',
            'cta' => 'Voir le cours →',
            'url' => (new moodle_url('/course/search.php', ['search' => 'anglais']))->out(false),
        ],
    ],
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
