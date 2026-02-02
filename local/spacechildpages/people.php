<?php
require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/people.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les personnes');
$PAGE->set_heading('Pour les personnes');
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
        ['label' => 'Pour les personnes', 'url' => $peopleurl->out(false), 'active' => true],
        ['label' => 'Pour les universités', 'url' => $universitiesurl->out(false)],
        ['label' => 'Pour les gouvernements', 'url' => $governmentsurl->out(false)],
    ],
    
    // HERO - Basé sur coursera.org
    'hero_title' => 'Apprenez sans limites',
    'hero_subtitle' => 'Démarrez, changez ou faites progresser votre carrière avec plus de 7 000 cours issus d\'universités et d\'entreprises leaders mondiales. Apprenez à votre rythme, suivez des parcours guidés par des experts, et obtenez des certificats reconnus pour valoriser vos compétences et booster votre employabilité. Accédez à des projets pratiques, des évaluations interactives et une communauté d\'apprenants pour rester motivé et atteindre vos objectifs.',
    'page_title' => 'Pour les personnes',
    'hidecta' => false,
    'hero_image' => (new moodle_url('/theme/spacechild/images/pers.jpg'))->out(false),
    'hero_image_alt' => 'Apprentissage individuel',
    'hero_image_full' => true,
    'hero_align_top' => true,
    'cta_primary_label' => 'Inscription gratuite',
    'cta_primary_url' => $signupurl->out(false),
    'cta_secondary_label' => 'Explorer les cours',
    'cta_secondary_url' => (new moodle_url('/course/search.php'))->out(false),
    
    // OBJECTIFS - "What brings you to Coursera today?"
    'goals' => [
        ['icon' => '🚀', 'title' => 'Démarrer ma carrière', 'text' => 'Lancez-vous dans un nouveau domaine professionnel'],
        ['icon' => '🔄', 'title' => 'Changer de carrière', 'text' => 'Reconvertissez-vous dans un secteur porteur'],
        ['icon' => '📈', 'title' => 'Progresser dans mon rôle', 'text' => 'Développez de nouvelles compétences'],
        ['icon' => '🎓', 'title' => 'Explorer de nouveaux sujets', 'text' => 'Apprenez pour le plaisir d\'apprendre'],
    ],
    
    // PARTENAIRES LOGOS
    'partners' => [
        ['name' => 'Google', 'logo' => 'google.png'],
        ['name' => 'IBM', 'logo' => 'ibm.png'],
        ['name' => 'Microsoft', 'logo' => 'microsoft.png'],
        ['name' => 'Meta', 'logo' => 'meta.png'],
        ['name' => 'Stanford', 'logo' => 'stanford.png'],
        ['name' => 'Yale', 'logo' => 'yale.png'],
    ],
    
    // CATÉGORIES - "Explore categories"
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    
    // PARCOURS D'APPRENTISSAGE - "What you can learn"
    'learning_paths' => [
        [
            'icon' => '🤖',
            'title' => 'Intelligence Artificielle',
            'description' => 'Maîtrisez le machine learning, le deep learning et l\'IA générative avec des formations de pointe.',
            'popular_courses' => 'Machine Learning, Deep Learning AI',
            'url' => (new moodle_url('/course/search.php', ['search' => 'ai']))->out(false),
        ],
        [
            'icon' => '📊',
            'title' => 'Data Science & Analyse',
            'description' => 'Analysez les données, créez des visualisations impactantes et prenez des décisions éclairées.',
            'popular_courses' => 'Google Data Analytics, IBM Data Analyst',
            'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
        ],
        [
            'icon' => '💼',
            'title' => 'Business & Management',
            'description' => 'Leadership, stratégie d\'entreprise, gestion de projet et entrepreneuriat digital.',
            'popular_courses' => 'Google Project Management, MBA Essentials',
            'url' => (new moodle_url('/course/search.php', ['search' => 'business']))->out(false),
        ],
        [
            'icon' => '💻',
            'title' => 'Développement Informatique',
            'description' => 'Full-stack, développement mobile, cloud computing et DevOps moderne.',
            'popular_courses' => 'Meta Front-End Developer, AWS Cloud',
            'url' => (new moodle_url('/course/search.php', ['search' => 'dev']))->out(false),
        ],
        [
            'icon' => '🔒',
            'title' => 'Cybersécurité',
            'description' => 'Protégez les systèmes et les données contre les cybermenaces actuelles.',
            'popular_courses' => 'Google Cybersecurity, IBM Security Analyst',
            'url' => (new moodle_url('/course/search.php', ['search' => 'cyber']))->out(false),
        ],
        [
            'icon' => '🎨',
            'title' => 'Design & UX',
            'description' => 'Créez des expériences utilisateur exceptionnelles et des interfaces intuitives.',
            'popular_courses' => 'Google UX Design, UI/UX Specialization',
            'url' => (new moodle_url('/course/search.php', ['search' => 'design']))->out(false),
        ],
    ],
    
    // COURS POPULAIRES
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    
    // TYPES DE PROGRAMMES - "Program types"
    'program_types' => [
        [
            'title' => 'Certificats Professionnels',
            'description' => 'Formations créées par Google, IBM, Microsoft et d\'autres leaders de l\'industrie pour vous préparer à des emplois en forte demande.',
            'duration' => '3-6 mois',
            'level' => 'Débutant',
            'example' => 'Exemple : Google Data Analytics Professional Certificate',
            'outcomes' => 'Compétences terrain + Certificat reconnu par les employeurs',
            'icon' => '🏆',
        ],
        [
            'title' => 'Spécialisations',
            'description' => 'Séries de cours pour maîtriser une compétence spécifique avec des projets pratiques.',
            'duration' => '1-3 mois',
            'level' => 'Tous niveaux',
            'example' => 'Exemple : Deep Learning Specialization (DeepLearning.AI)',
            'outcomes' => 'Expertise approfondie + Portfolio de projets',
            'icon' => '📚',
        ],
        [
            'title' => 'Cours Individuels',
            'description' => 'Apprenez rapidement une compétence spécifique avec des cours courts et ciblés.',
            'duration' => '2-8 semaines',
            'level' => 'Tous niveaux',
            'example' => 'Exemple : Machine Learning (Stanford University)',
            'outcomes' => 'Certificat de cours vérifiable',
            'icon' => '🎓',
        ],
        [
            'title' => 'Projets Guidés',
            'description' => 'Projets pratiques hands-on de 1-2 heures pour acquérir une compétence immédiate.',
            'duration' => '< 2 heures',
            'level' => 'Débutant-Intermédiaire',
            'example' => 'Exemple : Créer un site web avec Canva',
            'outcomes' => 'Compétence immédiatement applicable',
            'icon' => '⚡',
        ],
    ],
    
    // COMMENT ÇA MARCHE
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Trouvez votre formation',
            'text' => 'Explorez des milliers de cours, certificats et diplômes proposés par des universités et entreprises leaders comme Google, IBM, Stanford et Yale.',
        ],
        [
            'number' => '02',
            'title' => 'Apprenez avec les meilleurs',
            'text' => 'Suivez des cours créés par des experts reconnus mondialement. Accédez à des vidéos HD, quiz interactifs, projets pratiques et évaluations.',
        ],
        [
            'number' => '03',
            'title' => 'Obtenez votre certificat',
            'text' => 'Affichez vos nouvelles compétences sur LinkedIn et votre CV. 91% des apprenants obtiennent des bénéfices carrière positifs.',
        ],
    ],
    
    // TÉMOIGNAGES DÉTAILLÉS - Basés sur la vraie page Coursera
    'hasoutcomes' => true,
    'detailed_testimonials' => [
        [
            'quote' => 'La réputation de qualité de cette plateforme, associée à sa structure flexible, m\'a permis de me plonger dans l\'analyse de données tout en gérant famille, santé et vie quotidienne.',
            'name' => 'Sarah W.',
            'role' => 'Data Analyst',
            'previous_role' => 'Anciennement dans la vente au détail',
            'program' => 'Google Data Analytics Professional Certificate',
            'outcome' => 'Recrutée comme Data Analyst en 4 mois',
        ],
        [
            'quote' => 'Cette formation a reconstruit ma confiance et m\'a montré que je pouvais rêver plus grand. Ce n\'était pas juste acquérir des connaissances—c\'était croire en mon potentiel à nouveau.',
            'name' => 'Noeris B.',
            'role' => 'Business Analyst',
            'previous_role' => 'Manager retail pendant 15 ans',
            'program' => 'IBM Data Science Professional Certificate',
            'outcome' => 'Transition carrière réussie + 30% augmentation salaire',
        ],
        [
            'quote' => 'Je me sens maintenant plus préparée à assumer des rôles de leadership et j\'ai déjà commencé à encadrer certains de mes collègues dans mon entreprise.',
            'name' => 'Léa D.',
            'role' => 'UX Lead Designer',
            'previous_role' => 'Junior Designer',
            'program' => 'Google UX Design Professional Certificate',
            'outcome' => 'Promotion Lead + équipe de 5 personnes',
        ],
        [
            'quote' => 'Apprendre ici a élargi mon expertise professionnelle en me donnant accès à des recherches de pointe, des outils pratiques et des perspectives globales.',
            'name' => 'Anas A.',
            'role' => 'Machine Learning Engineer',
            'previous_role' => 'Développeur backend',
            'program' => 'Deep Learning Specialization',
            'outcome' => 'Nouvelle carrière en IA / +40% salaire',
        ],
    ],
    
    // BÉNÉFICES CARRIÈRE - Stats Coursera réelles
    'career_benefits' => [
        [
            'stat' => '91%',
            'title' => 'Bénéfices carrière positifs',
            'description' => '91% des apprenants rapportent au moins un bénéfice carrière positif (promotion, nouvel emploi, nouvelles compétences) dans les 6 mois.',
        ],
        [
            'stat' => '33%',
            'title' => 'Nouvel emploi obtenu',
            'description' => '33% des apprenants ont décroché un nouvel emploi après avoir terminé leur certificat professionnel.',
        ],
        [
            'stat' => '40%',
            'title' => 'Augmentation salariale',
            'description' => '40% des apprenants ont obtenu une augmentation de salaire ou une promotion après la formation.',
        ],
        [
            'stat' => '87%',
            'title' => 'Compétences appliquées',
            'description' => '87% utilisent les compétences acquises directement dans leur travail quotidien.',
        ],
    ],
    
    // FAQ ENRICHIE - 12 questions comme Coursera
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'Puis-je vraiment commencer gratuitement ?',
            'answer' => 'Oui ! Des centaines de cours sont disponibles en audit gratuit. Vous pouvez accéder aux vidéos et aux lectures. Pour obtenir le certificat et accéder aux exercices notés, vous devrez vous inscrire au cours payant.',
        ],
        [
            'question' => 'Les certificats sont-ils reconnus par les employeurs ?',
            'answer' => 'Oui. Nos Certificats Professionnels sont créés par Google, IBM, Microsoft et d\'autres leaders mondiaux. Ils sont reconnus internationalement. 91% des apprenants rapportent des bénéfices carrière après avoir terminé leur formation.',
        ],
        [
            'question' => 'Combien de temps faut-il pour terminer un Certificat Professionnel ?',
            'answer' => 'La plupart des Certificats Professionnels prennent 3 à 6 mois à raison de 10 heures par semaine. Vous pouvez aller plus vite ou plus lentement selon votre rythme personnel et vos disponibilités.',
        ],
        [
            'question' => 'Puis-je payer en plusieurs fois ?',
            'answer' => 'Oui. La plupart des programmes offrent des paiements mensuels flexibles. Les cours sont généralement proposés sous forme d\'abonnement mensuel.',
        ],
        [
            'question' => 'Y a-t-il des aides financières disponibles ?',
            'answer' => 'Oui. Nous offrons des aides financières sur demande pour ceux qui ne peuvent pas se permettre les frais. Faites une demande directement sur la page du cours qui vous intéresse.',
        ],
        [
            'question' => 'Les cours sont-ils auto-rythmés ou avec des dates fixes ?',
            'answer' => 'La plupart de nos cours sont flexibles et auto-rythmés. Vous apprenez à votre propre rythme, quand vous le souhaitez. Quelques programmes ont des dates de début suggérées, mais restent flexibles.',
        ],
        [
            'question' => 'Qu\'est-ce qu\'un Projet Guidé ?',
            'answer' => 'Un Projet Guidé est une expérience pratique hands-on de 1-2 heures où vous apprenez en faisant dans un environnement réel. Parfait pour acquérir rapidement une compétence spécifique.',
        ],
        [
            'question' => 'Les cours sont-ils disponibles en français ?',
            'answer' => 'Oui, de nombreux cours sont disponibles en français, ainsi qu\'en espagnol, allemand, portugais et 20+ autres langues avec sous-titres.',
        ],
        [
            'question' => 'Puis-je accéder aux cours sur mobile ?',
            'answer' => 'Oui ! Notre application mobile iOS et Android vous permet d\'apprendre partout, même hors ligne. Téléchargez les vidéos pour apprendre sans connexion internet.',
        ],
        [
            'question' => 'Puis-je partager mon certificat sur LinkedIn ?',
            'answer' => 'Oui ! Tous nos certificats incluent un badge digital que vous pouvez ajouter directement à votre profil LinkedIn et partager avec votre réseau professionnel.',
        ],
        [
            'question' => 'Comment les cours m\'aident-ils à progresser dans ma carrière ?',
            'answer' => 'Nos formations sont conçues en collaboration avec des employeurs leaders. Elles enseignent les compétences les plus recherchées du marché et vous préparent à des rôles concrets en cybersécurité, data analytics, UX design, IA et business.',
        ],
        [
            'question' => 'Quelle est la différence entre un cours et une Spécialisation ?',
            'answer' => 'Un cours individuel enseigne une compétence spécifique (2-8 semaines). Une Spécialisation est une série de cours liés qui vous permet de maîtriser un domaine complet (1-3 mois) avec un projet final.',
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
