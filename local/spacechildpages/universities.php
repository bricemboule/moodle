<?php
require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/universities.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les universités');
$PAGE->set_heading('Pour les universités');
$PAGE->requires->css(new moodle_url('/theme/spacechild/style/marketing.css'));
$PAGE->requires->css(new moodle_url('/theme/spacechild/style/navigation-coursera.css'));
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
    $marketingcategories = \local_spacechildpages\marketing_categories::get_categories(12);
}
$marketingcourses = [];
if (class_exists('\\local_spacechildpages\\marketing_courses')) {
    $marketingcourses = \local_spacechildpages\marketing_courses::get_courses(12);
}

$ctx = [
    'config' => ['wwwroot' => $CFG->wwwroot],
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
        ['label' => 'Pour les personnes', 'url' => $peopleurl->out(false)],
        ['label' => 'Pour les universités', 'url' => $universitiesurl->out(false), 'active' => true],
        ['label' => 'Pour les gouvernements', 'url' => $governmentsurl->out(false)],
    ],
    
    // HERO - Basé sur coursera.org/campus
    'hero_title' => 'Renforcez l\'Employabilité Pour Attirer Plus d\'Etudiants',
    'hero_subtitle' => 'Équipez vos étudiants des compétences les plus recherchées et préparez-les au succès professionnel avec 10 600+ cours issus de 350+ universités et entreprises leaders.',
    'page_title' => 'Pour les universités',
    'hidecta' => false,
    'hero_justify' => true,
    'hero_image' => (new moodle_url('/theme/spacechild/images/univ.jpg'))->out(false),
    'hero_image_alt' => 'Campus universitaire',
    'hero_image_full' => true,
    'hero_align_top' => true,
    'cta_primary_label' => 'Nous contacter',
    'cta_primary_url' => $supporturl->out(false),
    'cta_secondary_label' => 'Comparer les formules',
    'cta_secondary_url' => $supporturl->out(false),
    
    // STATS CAMPUS - Vraies stats Coursera Campus
    'campus_stats' => [
        ['value' => '76%', 'label' => 'des étudiants préfèrent les programmes avec micro-credentials'],
        ['value' => '88%', 'label' => 'des employeurs valorisent les Certificats Professionnels'],
        ['value' => '90%', 'label' => 'des étudiants pensent qu\'un Certificat les aidera à trouver un emploi'],
    ],
    
    // PARTENAIRES UNIVERSITÉS
    'university_partners' => [
        ['name' => 'Google', 'logo' => 'google.png'],
        ['name' => 'IBM', 'logo' => 'ibm.png'],
        ['name' => 'Microsoft', 'logo' => 'microsoft.png'],
        ['name' => 'Stanford University', 'logo' => 'stanford.png'],
        ['name' => 'Yale University', 'logo' => 'yale.png'],
        ['name' => 'Imperial College London', 'logo' => 'imperial.png'],
    ],
    
    // CAREER ACADEMY - Section phare de Coursera Campus
    'career_academy' => [
        'title' => 'Préparez vos étudiants aux emplois en forte demande',
        'subtitle' => 'Renforcez l\'employabilité étudiante avec des formations des leaders mondiaux.',
        'benefits' => [
            'Obtenir un Certificat Professionnel conçu pour être job-ready',
            'Acquérir les compétences communes que les employeurs recherchent',
            'Démontrer la maîtrise des compétences avec un portfolio de projets',
            'Explorer une gamme de rôles en forte demande dans différentes industries',
        ],
    ],
    
    // POURQUOI NOUS CHOISIR
    'why_campus' => [
        [
            'icon' => '🎓',
            'title' => 'Contenu de classe mondiale',
            'description' => 'Connectez vos étudiants à un large éventail de contenus issus de centaines de leaders de l\'industrie et d\'universités prestigieuses.',
        ],
        [
            'icon' => '💼',
            'title' => 'Projets Guidés pratiques',
            'description' => 'Offrez aux étudiants des projets hands-on pour pratiquer leurs compétences et se démarquer auprès des employeurs.',
        ],
        [
            'icon' => '🏆',
            'title' => 'Certificats Professionnels',
            'description' => 'Aidez vos étudiants à développer leur confiance professionnelle, appliquer leurs apprentissages et perfectionner leurs compétences critiques.',
        ],
        [
            'icon' => '🔌',
            'title' => 'Intégration LMS',
            'description' => 'Simplifiez l\'expérience d\'apprentissage en reliant notre plateforme à votre système de gestion de l\'apprentissage existant.',
        ],
    ],
    
    // CATÉGORIES
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    
    // COURS
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    
    // CAS D'USAGE UNIVERSITÉS - Comment les universités utilisent la plateforme
    'university_use_cases' => [
        [
            'title' => 'Compléter le curriculum',
            'description' => 'Enrichissez vos cours existants avec des modules spécialisés issus d\'experts de l\'industrie et du monde académique.',
            'example' => 'Duke University utilise Machine Learning de Stanford dans son MBA',
            'result' => 'Contenu de pointe sans coût de développement',
            'icon' => '📚',
        ],
        [
            'title' => 'Upskilling du corps enseignant',
            'description' => 'Formez vos professeurs aux nouvelles technologies et méthodologies pédagogiques innovantes.',
            'example' => 'University of Michigan forme 500 enseignants à l\'IA',
            'result' => 'Corps enseignant au top de la technologie',
            'icon' => '👨‍🏫',
        ],
        [
            'title' => 'Programmes de réussite étudiante',
            'description' => 'Parcours d\'onboarding, développement de compétences professionnelles et préparation carrière.',
            'example' => 'Manipal Academy - 20 000 étudiants formés',
            'result' => 'Employabilité étudiante significativement améliorée',
            'icon' => '🎯',
        ],
        [
            'title' => 'Lifelong Learning Alumni',
            'description' => 'Maintenez l\'engagement de vos diplômés avec un accès continu à des formations professionnelles.',
            'example' => 'Duke offre un accès illimité à tous ses alumni',
            'result' => 'Communauté d\'alumni engagée à vie',
            'icon' => '🔄',
        ],
        [
            'title' => 'Cours créditables',
            'description' => 'Offrez du contenu pour l\'obtention de crédits universitaires avec reconnaissance académique.',
            'example' => 'University of Illinois - Master in Computer Science',
            'result' => 'Programmes en ligne accrédités à grande échelle',
            'icon' => '🎓',
        ],
        [
            'title' => 'Formation continue professionnelle',
            'description' => 'Programmes executive et formation continue pour adultes et professionnels en activité.',
            'example' => 'Symbiosis Institute - programmes executive',
            'result' => 'Nouvelles sources de revenus pour l\'institution',
            'icon' => '💼',
        ],
    ],
    
    // TÉMOIGNAGES UNIVERSITÉS - Vraies citations Coursera Campus
    'hasoutcomes' => true,
    'university_testimonials' => [
        [
            'quote' => 'Coursera nous donne confiance que nous offrons à nos étudiants une éducation de haute qualité qui favorise leurs opportunités de carrière. Sans Coursera, nous ne pourrions pas devenir une "Université 4.0".',
            'name' => 'Yevgenia D.',
            'title' => 'Vice-Rectrice pour la Science et la Collaboration Internationale',
            'university' => 'International Information Technology University (IITU)',
            'logo' => 'iitu.png',
        ],
        [
            'quote' => 'Aucun professeur ou université ne peut offrir seul l\'étendue de choix que les étudiants ont avec cette plateforme. C\'est un excellent pont entre la salle de classe et le monde du travail.',
            'name' => 'Lameck O.',
            'title' => 'Professeur et Chef de projet IT',
            'university' => 'Ivey Business School',
            'logo' => 'ivey.png',
        ],
    ],
    
    // VALEUR DES MICRO-CREDENTIALS
    'micro_credentials_value' => [
        'title' => 'Pourquoi les étudiants et employeurs valorisent les Certificats Professionnels',
        'description' => 'Une enquête auprès de 5 000 étudiants et employeurs dans 11 pays révèle que la majorité valorise les Certificats Professionnels pour leurs résultats en matière d\'emploi.',
        'stats' => [
            ['value' => '76%', 'label' => 'plus susceptibles de s\'inscrire avec micro-credentials'],
            ['value' => '88%', 'label' => 'des employeurs valorisent les Certificats'],
            ['value' => '90%', 'label' => 'des étudiants pensent que cela aide à trouver un emploi'],
        ],
    ],
    
    // COMMENT ÇA MARCHE
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Connectez curriculum et carrières',
            'text' => 'Intégrez des contenus professionnels reconnus par l\'industrie directement dans vos programmes académiques pour renforcer l\'employabilité.',
        ],
        [
            'number' => '02',
            'title' => 'Renforcez les résultats emploi',
            'text' => 'Équipez vos étudiants avec des compétences job-ready et des certificats valorisés par les employeurs leaders mondiaux.',
        ],
        [
            'number' => '03',
            'title' => 'Enrichissez l\'expérience d\'apprentissage',
            'text' => 'Donnez accès à des cours de classe mondiale, des projets pratiques et des technologies d\'apprentissage innovantes.',
        ],
    ],
    
    // FAQ UNIVERSITÉS
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'Peut-on intégrer vos contenus à notre LMS existant ?',
            'answer' => 'Oui, absolument. Nous proposons des intégrations natives avec plus de 30 systèmes LMS/LXP incluant Canvas, Blackboard, Moodle, et autres. Les notes et la progression se synchronisent automatiquement.',
        ],
        [
            'question' => 'Existe-t-il des tableaux de bord pour les enseignants ?',
            'answer' => 'Oui. Des tableaux de bord complets permettent aux enseignants de suivre la progression de chaque étudiant, identifier ceux en difficulté, analyser les taux de complétion et exporter des rapports détaillés.',
        ],
        [
            'question' => 'Comment personnaliser l\'expérience pour notre établissement ?',
            'answer' => 'La personnalisation est totale : branding (logo, couleurs), navigation personnalisée, parcours recommandés sur mesure, contenus spécifiques à votre institution et développements custom si nécessaire.',
        ],
        [
            'question' => 'Comment gérez-vous les données des étudiants (RGPD) ?',
            'answer' => 'Nous sommes 100% conformes RGPD et FERPA. Les données sont hébergées en Europe (option disponible), chiffrées, avec gestion fine des consentements. Vous gardez le contrôle total des données de vos étudiants.',
        ],
        [
            'question' => 'Proposez-vous de la formation pour nos enseignants ?',
            'answer' => 'Oui, absolument. Nous accompagnons vos équipes pédagogiques : formation à la plateforme, best practices en digital learning, accompagnement à la création de contenus et support technique continu.',
        ],
        [
            'question' => 'Quel est le délai de mise en place ?',
            'answer' => 'Le déploiement standard prend 4 à 8 semaines selon la complexité et vos besoins d\'intégration. Nous proposons une approche progressive pour minimiser les risques et impliquer toutes les parties prenantes.',
        ],
        [
            'question' => 'Les étudiants peuvent-ils obtenir des crédits académiques ?',
            'answer' => 'Oui. De nombreuses universités offrent des crédits pour nos cours. Vous décidez quels cours sont éligibles et combien de crédits ils valent dans votre système.',
        ],
        [
            'question' => 'Quels types de contenu sont disponibles ?',
            'answer' => 'Plus de 10 600 cours, Spécialisations, Certificats Professionnels, Projets Guidés et même des diplômes complets (Bachelors et Masters). Contenus couvrant Business, Data, IT, IA, Design et bien plus.',
        ],
    ],
];

$presencekeys = [
    'campus_stats',
    'government_stats',
    'partners',
    'goals',
    'learning_paths',
    'program_types',
    'why_campus',
    'university_use_cases',
    'government_training_features',
    'skills_tracks',
    'government_solutions',
    'career_benefits',
    'detailed_testimonials',
    'university_testimonials',
    'government_case_studies',
];

foreach ($presencekeys as $key) {
    $ctx['has' . $key] = !empty($ctx[$key]);
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_spacechild/audience', $ctx);
echo $OUTPUT->footer();
