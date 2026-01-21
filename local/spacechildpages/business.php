<?php
require_once('../../config.php');

use local_spacechildpages\form\business_contact_form;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/spacechildpages/business.php'));
$PAGE->set_pagelayout('embedded');
$PAGE->set_title('Pour les affaires');
$PAGE->set_heading('Pour les affaires');
$PAGE->requires->js(new moodle_url('/theme/spacechild/javascript/marketing.js'));

require_once($CFG->dirroot . '/local/spacechildpages/classes/form/business_contact_form.php');

$peopleurl = new moodle_url('/local/spacechildpages/people.php');
$businessurl = new moodle_url('/local/spacechildpages/business.php');
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

$mform = new business_contact_form();

if ($data = $mform->get_data()) {
    // Envoi email sans plugin externe (core moodle)
    $admin = get_admin();
    $support = \core_user::get_support_user();

    $subject = '[Business] Demande de contact - ' . $data->company;
    $text = "Nouvelle demande business\n\n"
        . "Nom: {$data->fullname}\n"
        . "Email: {$data->email}\n"
        . "Entreprise: {$data->company}\n"
        . "Téléphone: {$data->phone}\n"
        . "Taille équipe: {$data->teamsize}\n\n"
        . "Message:\n{$data->message}\n";

    email_to_user($admin, $support, $subject, $text);

    redirect(new moodle_url('/local/spacechildpages/business.php', ['sent' => 1]), 'Merci, votre demande a été envoyée ✅', 2);
}

$sent = optional_param('sent', 0, PARAM_BOOL);

ob_start();
$mform->display();
$formhtml = ob_get_clean();

$notice = '';
if ($sent) {
    $notice = '<div class="cs-card" style="border-color: var(--cs-primary); background: #f0fdf4; color: #166534; font-weight: 700;">Demande envoyée ✅</div>';
}

$pricing = '<section id="pricing" class="cs-section cs-section--muted">'
    . '<div class="cs-container">'
    . '<div class="cs-section__head"><h2>Offres</h2></div>'
    . '<div class="cs-grid cs-grid--3">'
    . '<article class="cs-card">'
        . '<div class="cs-card__title">Starter</div>'
        . '<div class="cs-card__meta">0 € • Pour démarrer et tester.</div>'
        . '<ul style="color:var(--cs-muted);font-size:13px;line-height:1.7;padding-left:18px;">'
            . '<li>Catalogue de base</li><li>Suivi simple</li><li>Support email</li>'
        . '</ul>'
        . '<a class="cs-link" href="#contact">Commencer →</a>'
    . '</article>'
    . '<article class="cs-card">'
        . '<div class="cs-card__title">Team</div>'
        . '<div class="cs-card__meta">Sur devis • Pour équipes & organisations.</div>'
        . '<ul style="color:var(--cs-muted);font-size:13px;line-height:1.7;padding-left:18px;">'
            . '<li>Reporting avancé</li><li>Parcours par rôle</li><li>Support prioritaire</li>'
        . '</ul>'
        . '<a class="cs-link" href="#contact">Demander une offre →</a>'
    . '</article>'
    . '<article class="cs-card">'
        . '<div class="cs-card__title">Enterprise</div>'
        . '<div class="cs-card__meta">Sur devis • Déploiement à grande échelle.</div>'
        . '<ul style="color:var(--cs-muted);font-size:13px;line-height:1.7;padding-left:18px;">'
            . '<li>Branding complet</li><li>Intégrations</li><li>Accompagnement dédié</li>'
        . '</ul>'
        . '<a class="cs-link" href="#contact">Parler à un expert →</a>'
    . '</article>'
    . '</div>'
    . '</div>'
    . '</section>';

$contact = '<section id="contact" class="cs-section">'
    . '<div class="cs-container">'
    . '<div class="cs-section__head"><h2>Contact</h2></div>'
    . '<p class="cs-lead">Décrivez votre besoin, on vous répond rapidement.</p>'
    . $notice
    . '<div class="cs-card">' . $formhtml . '</div>'
    . '</div>'
    . '</section>';

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
    'businessurl' => $businessurl->out(false),
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
            'label' => 'Pour les affaires',
            'url' => $businessurl->out(false),
            'active' => true,
        ],
        [
            'label' => 'Pour les gouvernements',
            'url' => $governmentsurl->out(false),
        ],
    ],
    'hero_title' => 'Former vos équipes, mesurer l’impact.',
    'hero_subtitle' => 'Plans simples, reporting et support pour accélérer votre organisation.',
    'page_title' => 'Pour les affaires',
    'hero_badge' => 'Landing',
    'hero_card_title' => 'Pour les affaires',
    'hero_card_text' => 'Une page marketing dédiée aux entreprises, avec des parcours ciblés et des visuels adaptés.',
    'hero_image' => (new moodle_url('/theme/spacechild/pix/landing/course-product.svg'))->out(false),
    'hero_image_alt' => 'Illustration équipes et entreprises',
    'cta_primary_label' => 'Demander une offre',
    'cta_primary_url' => $businessurl->out(false) . '#contact',
    'cta_secondary_label' => 'Voir les plans',
    'cta_secondary_url' => $businessurl->out(false) . '#pricing',
    'features' => [
        [
            'title' => 'Reporting avancé',
            'text' => 'Suivi par équipe, progression et indicateurs clairs.',
        ],
        [
            'title' => 'Parcours par rôle',
            'text' => 'Plans adaptés aux métiers et aux niveaux.',
        ],
        [
            'title' => 'Support prioritaire',
            'text' => 'Accompagnement et réponses rapides.',
        ],
    ],
    'hascategories' => !empty($marketingcategories),
    'categories' => $marketingcategories,
    'hascourses' => !empty($marketingcourses),
    'courses' => $marketingcourses,
    'hasprograms' => true,
    'programs' => [
        [
            'tag' => 'Upskilling',
            'title' => 'Académie Data & IA',
            'summary' => 'Former rapidement vos équipes aux usages data.',
            'details' => '6 parcours • 10 semaines • Labs inclus',
            'cta' => 'Voir les parcours',
            'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
        ],
        [
            'tag' => 'Leadership',
            'title' => 'Management & Product',
            'summary' => 'Alignement stratégique et delivery efficace.',
            'details' => '5 parcours • Coaching managers',
            'cta' => 'Découvrir',
            'url' => (new moodle_url('/course/search.php', ['search' => 'management']))->out(false),
        ],
        [
            'tag' => 'Onboarding',
            'title' => 'Parcours d’intégration',
            'summary' => 'Mettre vos nouveaux arrivants à niveau.',
            'details' => 'Modules courts • Suivi RH',
            'cta' => 'Explorer',
            'url' => (new moodle_url('/course/search.php', ['search' => 'onboarding']))->out(false),
        ],
    ],
    'hasoutcomes' => true,
    'outcomes' => [
        [
            'value' => '-35%',
            'label' => 'Temps de montée en compétence',
        ],
        [
            'value' => '80%',
            'label' => 'Taux d’adoption interne',
        ],
        [
            'value' => '4.7/5',
            'label' => 'Satisfaction des équipes',
        ],
    ],
    'hassteps' => true,
    'steps' => [
        [
            'number' => '01',
            'title' => 'Diagnostic compétences',
            'text' => 'Cartographie des rôles et des besoins.',
        ],
        [
            'number' => '02',
            'title' => 'Déploiement rapide',
            'text' => 'Parcours prêts à l’emploi ou sur-mesure.',
        ],
        [
            'number' => '03',
            'title' => 'Mesure d’impact',
            'text' => 'Reporting et KPIs de progression.',
        ],
    ],
    'hascasestudies' => true,
    'casestudies' => [
        [
            'title' => 'Groupe retail',
            'text' => 'Formation de 120 managers en 8 semaines.',
            'result' => 'Productivité +18%',
        ],
        [
            'title' => 'Fintech',
            'text' => 'Montée en compétences data pour 60 collaborateurs.',
            'result' => 'Time-to-insight réduit de 30%',
        ],
        [
            'title' => 'Industrie',
            'text' => 'Parcours sécurité & conformité déployé.',
            'result' => 'Audit réussi sans non-conformité',
        ],
    ],
    'hasfaq' => true,
    'faq' => [
        [
            'question' => 'Peut-on intégrer un SSO ?',
            'answer' => 'Oui, nous supportons SSO/LDAP selon votre environnement.',
        ],
        [
            'question' => 'Le reporting est-il exportable ?',
            'answer' => 'Des exports CSV et tableaux de bord sont disponibles.',
        ],
        [
            'question' => 'Proposez-vous du sur-mesure ?',
            'answer' => 'Oui, nous adaptons les parcours à vos objectifs.',
        ],
    ],
    'extra_html' => $pricing . $contact,
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_spacechild/audience', $ctx);
echo $OUTPUT->footer();
