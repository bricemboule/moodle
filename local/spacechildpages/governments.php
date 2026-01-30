<?php
require_once('../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/governments.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les gouvernements');
$PAGE->set_heading('Pour les gouvernements');
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
        ['label' => 'Pour les universités', 'url' => $universitiesurl->out(false)],
        ['label' => 'Pour les gouvernements', 'url' => $governmentsurl->out(false), 'active' => true],
    ],
    
    // HERO - Basé sur coursera.org/government
    'hero_title' => 'Accélérez La Croissance Du Secteur Public Avec Une Plateforme d\'Aprentissage Gouvernementale',
    'hero_subtitle' => 'Stimulez une croissance économique durable et bâtissez une main-d\'œuvre compétitive avec une plateforme e-learning gouvernementale proposant des cours des meilleures universités et entreprises. Partenariat avec plus de 900 organisations gouvernementales dans 100+ pays. Offrez des parcours certifiants, un suivi précis des compétences et des résultats mesurables pour moderniser vos services publics.',
    'page_title' => 'Pour les gouvernements',
    'hidecta' => false,
    'hero_title_small' => true,
    'hero_justify' => true,
    'hero_image' => (new moodle_url('/theme/spacechild/images/gouverne.png'))->out(false),
    'hero_image_alt' => 'Institutions gouvernementales',
    'hero_image_full' => true,
    'cta_primary_label' => 'Demander une démo',
    'cta_primary_url' => $supporturl->out(false),
    'cta_secondary_label' => 'En savoir plus',
    'cta_secondary_url' => $supporturl->out(false),
    
    // STATS GOUVERNEMENT - Vraies stats Coursera Government
    'government_stats' => [
        ['value' => '5x', 'label' => 'Recruter par compétences est 5x plus prédictif que par diplôme'],
        ['value' => '32%', 'label' => 'de la population mondiale n\'est pas en ligne (fracture numérique)'],
        ['value' => '39%', 'label' => 'des compétences existantes seront obsolètes d\'ici 2030'],
    ],
    
    // PARTENAIRES GOUVERNEMENT
    'government_partners' => [
        ['name' => 'Microsoft', 'logo' => 'microsoft.png'],
        ['name' => 'Google Cloud', 'logo' => 'google-cloud.png'],
        ['name' => 'AWS', 'logo' => 'aws.png'],
        ['name' => 'IBM', 'logo' => 'ibm.png'],
        ['name' => 'NVIDIA', 'logo' => 'nvidia.png'],
        ['name' => 'DeepLearning.AI', 'logo' => 'deeplearning.png'],
    ],
    
    // CONTENU DE HAUTE QUALITÉ
    'high_quality_content' => [
        'title' => 'Donnez aux équipes gouvernementales l\'accès à du contenu de classe mondiale',
        'subtitle' => 'Gardez une longueur d\'avance sur les technologies émergentes en développant et requalifiant les fonctionnaires et citoyens avec des compétences en forte demande.',
        'benefits' => [
            'Formation alignée sur l\'emploi pour combler rapidement les lacunes de compétences numériques',
            'Formats de contenu diversifiés, des clips vidéo aux micro-credentials, adaptés aux préférences d\'apprentissage',
            'Apprentissage disponible en plus de 25 langues pour soutenir les apprenants dans leur langue maternelle',
            'Skills Tracks pour la maîtrise de compétences spécifiques à des rôles en forte demande',
        ],
    ],
    
    // FORMATION GOUVERNEMENTALE AVEC IMPACT
    'government_training_features' => [
        [
            'icon' => '🌍',
            'title' => 'Apprentissage en 25+ langues',
            'description' => 'Fournissez une formation dans la langue maternelle des apprenants avec accès à 5 500+ cours en arabe, espagnol, français, allemand, thaï, ourdou et plus encore.',
        ],
        [
            'icon' => '🔑',
            'title' => 'Intégration dans votre écosystème',
            'description' => 'Connectez facilement notre plateforme gouvernementale avec plus de 30 systèmes LMS et LXP pour unifier les données, simplifier la gestion et surveiller le développement de la main-d\'œuvre.',
        ],
        [
            'icon' => '⚙️',
            'title' => 'Personnalisation de la formation',
            'description' => 'Accélérez la création et la curation de contenu avec des outils alimentés par l\'IA pour adapter l\'apprentissage aux objectifs et besoins de votre organisation.',
        ],
        [
            'icon' => '🛡️',
            'title' => 'Pratique sécurisée',
            'description' => 'Favorisez l\'application dans le monde réel avec une pratique hands-on dans des environnements LLM privés et sécurisés, réduisant le temps entre l\'apprentissage et l\'application.',
        ],
    ],
    
    // SKILLS TRACKS - Section majeure de Coursera Government
    'skills_tracks' => [
        [
            'title' => 'Data Skills Track',
            'subtitle' => 'Découvrir des insights',
            'description' => 'Renforcez la prise de décision avec des parcours d\'apprentissage en analyse commerciale, gestion des données et automatisation des workflows. Aidez les équipes data science et opérations à améliorer leurs capacités d\'analyse et de prévision pour faire des prédictions plus rapides et précises.',
            'icon' => '📊',
            'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
        ],
        [
            'title' => 'IT Skills Track',
            'subtitle' => 'Moderniser les systèmes',
            'description' => 'Renforcez la sécurité et optimisez les stacks technologiques avec des parcours en cybersécurité, opérations IT et administration réseau pour maintenir les systèmes en ligne et gérer les risques de sécurité dans un paysage de menaces évolutif.',
            'icon' => '💻',
            'url' => (new moodle_url('/course/search.php', ['search' => 'it']))->out(false),
        ],
        [
            'title' => 'GenAI Skills Track',
            'subtitle' => 'Piloter la transformation digitale',
            'description' => 'Donnez du pouvoir aux équipes à travers toutes les fonctions et niveaux de carrière avec des parcours en IA générative. Des analystes aux ingénieurs, équipez votre équipe pour utiliser avec confiance les outils IA afin d\'améliorer les workflows, automatiser les tâches chronophages et augmenter la productivité.',
            'icon' => '🤖',
            'url' => (new moodle_url('/course/search.php', ['search' => 'ai']))->out(false),
        ],
    ],
    
    // CATÉGORIES
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    
    // COURS
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    
    // SOLUTIONS GOUVERNEMENT
    'government_solutions' => [
        [
            'title' => 'Formation des agents publics',
            'description' => 'Upskilling et reskilling à grande échelle pour la fonction publique avec des parcours obligatoires et un tracking complet.',
            'features' => [
                'Architecture multi-organisations sécurisée',
                'Gestion par ministère / région',
                'Parcours obligatoires avec tracking',
                'Reporting consolidé au niveau national',
            ],
            'icon' => '👥',
        ],
        [
            'title' => 'Développement de la main-d\'œuvre',
            'description' => 'Préparez les citoyens aux emplois de demain avec des certificats professionnels reconnus par l\'industrie.',
            'features' => [
                'Certificats Professionnels reconnus',
                'Programmes sectoriels ciblés',
                'Partenariats avec employeurs',
                'Support au placement professionnel',
            ],
            'icon' => '💼',
        ],
        [
            'title' => 'Innovation du secteur public',
            'description' => 'Transformez les services publics avec les nouvelles technologies : IA, automatisation, service design.',
            'features' => [
                'IA et automatisation des processus',
                'Transformation digitale complète',
                'Service Design thinking',
                'Change management stratégique',
            ],
            'icon' => '🚀',
        ],
    ],
    
    // ORGANISATIONS PARTENAIRES - Vraies organisations Coursera Government
    'partner_organizations' => [
        ['name' => 'AARP', 'logo' => 'aarp.png'],
        ['name' => 'US Department of Health', 'logo' => 'hhs.png'],
        ['name' => 'Arab Monetary Fund', 'logo' => 'amf.png'],
        ['name' => 'Dubai Police', 'logo' => 'dubai-police.png'],
        ['name' => 'Central Bank of Oman', 'logo' => 'cbo.png'],
        ['name' => 'Barbados National Transformation', 'logo' => 'barbados.png'],
    ],
    
    // COMMENT ÇA MARCHE
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Développer des compétences en forte demande',
            'text' => 'Répondez aux besoins changeants du marché du travail avec des formations alignées sur les emplois du futur et les technologies émergentes.',
        ],
        [
            'number' => '02',
            'title' => 'Engager et retenir les talents',
            'text' => 'Attirez et conservez des talents motivés par la mission publique en leur offrant des opportunités continues de développement professionnel.',
        ],
        [
            'number' => '03',
            'title' => 'Stimuler la croissance économique durable',
            'text' => 'Construisez une main-d\'œuvre compétitive et innovante qui stimule la transformation numérique et la prospérité économique nationale.',
        ],
    ],
    
    // TÉMOIGNAGES / CAS CLIENTS
    'hasoutcomes' => true,
    'government_case_studies' => [
        [
            'client' => 'Organisation gouvernementale nationale',
            'challenge' => 'Former massivement les agents publics aux compétences numériques essentielles',
            'solution' => 'Déploiement d\'une plateforme multi-tenant avec parcours obligatoires et tracking',
            'results' => [
                '15 000+ agents formés en 6 mois',
                '92% taux de complétion',
                'Transformation digitale accélérée',
            ],
        ],
        [
            'client' => 'Agence de développement économique',
            'challenge' => 'Préparer les citoyens sans emploi aux métiers du digital',
            'solution' => 'Accès gratuit aux Certificats Professionnels en Data, IT, Cybersécurité',
            'results' => [
                '8 000+ citoyens formés',
                '65% obtention certificat',
                '45% retour à l\'emploi accéléré',
            ],
        ],
        [
            'client' => 'Banque Centrale',
            'challenge' => 'Former le personnel aux nouvelles régulations et technologies financières',
            'solution' => 'Parcours sur mesure FinTech, Cybersécurité, Blockchain, Data Analytics',
            'results' => [
                '100% du personnel formé',
                'Conformité réglementaire atteinte',
                'Innovation accélérée',
            ],
        ],
    ],
    
    // FAQ GOUVERNEMENT
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'Combien d\'organisations gouvernementales utilisent votre plateforme ?',
            'answer' => 'Plus de 900 organisations gouvernementales dans plus de 100 pays font confiance à notre plateforme pour former leurs agents et citoyens, incluant des agences fédérales, banques centrales, ministères et organismes publics.',
        ],
        [
            'question' => 'Les contenus sont-ils disponibles en plusieurs langues ?',
            'answer' => 'Oui. Plus de 5 500 cours sont disponibles en 25+ langues incluant français, arabe, espagnol, allemand, portugais, thaï, ourdou et bien d\'autres, permettant l\'apprentissage dans la langue maternelle.',
        ],
        [
            'question' => 'Comment assurez-vous la conformité et la sécurité des données ?',
            'answer' => 'Nous sommes conformes RGPD, offrons un hébergement souverain en option, chiffrement end-to-end, authentification multi-facteurs, audit trails complets et certifications de sécurité incluant ISO 27001 et FedRAMP (US).',
        ],
        [
            'question' => 'Peut-on créer des parcours de formation obligatoires ?',
            'answer' => 'Oui, absolument. La plateforme permet de définir des parcours obligatoires, de tracker la complétion, d\'envoyer des rappels automatiques et de générer des rapports de conformité détaillés par département ou région.',
        ],
        [
            'question' => 'Comment gérez-vous les déploiements multi-organisations ?',
            'answer' => 'Notre architecture multi-tenant permet de créer des espaces cloisonnés par ministère, région ou agence, avec délégation de gestion locale tout en conservant une vue consolidée et des rapports au niveau national.',
        ],
        [
            'question' => 'Proposez-vous des Skills Tracks spécifiques au secteur public ?',
            'answer' => 'Oui. Nous offrons des Skills Tracks sur mesure pour les gouvernements : Data & Analytics pour décisions publiques, IT & Cybersécurité pour infrastructure critique, et GenAI pour modernisation des services.',
        ],
        [
            'question' => 'Quel est le modèle tarifaire pour les gouvernements ?',
            'answer' => 'Nous proposons des licences au forfait ou par siège avec des tarifs préférentiels pour le secteur public. Le pricing varie selon le volume, les fonctionnalités et l\'engagement (annuel/pluriannuel). Contactez-nous pour un devis personnalisé.',
        ],
        [
            'question' => 'Comment mesurez-vous l\'impact de la formation ?',
            'answer' => 'La plateforme fournit des analytics avancés : taux de complétion, temps d\'apprentissage, compétences acquises, progression par région, impact sur les KPIs opérationnels et ROI de la formation avec rapports pour décideurs.',
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
