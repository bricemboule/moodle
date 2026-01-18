<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_spacechild\output\core\course;

use context_course;
use core_course_category;
use coursecat_helper;
use moodle_url;

/**
 * Course renderer overrides for the child theme.
 *
 * @package   theme_spacechild
 * @copyright 2022 - 2023 Marcin Czaja (https://rosea.io)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \theme_space\output\core\course_renderer {

    /**
     * Render the frontpage content.
     *
     * @return string
     */
    public function frontpage() {
        if (!isloggedin() || isguestuser()) {
            return $this->guest_frontpage();
        }

        return parent::frontpage();
    }

    /**
     * Custom landing content for guests.
     *
     * @return string
     */
    protected function guest_frontpage(): string {
        global $CFG, $SITE, $DB;

        $categories = $this->get_frontpage_categories(4);
        $courses = $this->get_frontpage_courses(4);
        $categoryplaceholders = $this->category_placeholder_images();
        $courseplaceholders = $this->course_placeholder_images();

        if (count($categories) < 4) {
            $fallbackcategories = [
                [
                    'name' => 'Developpement web',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'web']))->out(false),
                ],
                [
                    'name' => 'Data & IA',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
                ],
                [
                    'name' => 'Gestion de projet',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'projet']))->out(false),
                ],
                [
                    'name' => 'Marketing digital',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'marketing']))->out(false),
                ],
                [
                    'name' => 'Langues',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'langues']))->out(false),
                ],
                [
                    'name' => 'Bureautique',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'office']))->out(false),
                ],
            ];

            foreach ($fallbackcategories as $fallback) {
                $categories[] = $fallback;
                if (count($categories) >= 4) {
                    break;
                }
            }
        }

        if (count($courses) < 4) {
            $fallbackcourses = [
                [
                    'title' => 'Excel pour debutants',
                    'summary' => 'Bases Excel, tableaux, formules et graphiques.',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'excel']))->out(false),
                ],
                [
                    'title' => 'Python pour debutants',
                    'summary' => 'Scripts simples, data et automatisation.',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'python']))->out(false),
                ],
                [
                    'title' => 'Gestion de projet agile',
                    'summary' => 'Planifier, livrer, gerer risques et equipes.',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'agile']))->out(false),
                ],
                [
                    'title' => 'Communication professionnelle',
                    'summary' => 'E-mails, presentations et prise de parole.',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'communication']))->out(false),
                ],
                [
                    'title' => 'Marketing digital',
                    'summary' => 'SEO, reseaux sociaux et campagnes.',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'marketing']))->out(false),
                ],
            ];

            foreach ($fallbackcourses as $fallback) {
                $courses[] = $fallback;
                if (count($courses) >= 4) {
                    break;
                }
            }
        }

        foreach ($categories as $index => $category) {
            if (empty($category['image'])) {
                $seed = $category['name'] ?? $index;
                $categories[$index]['image'] = $this->pick_placeholder_image($categoryplaceholders, $seed);
            }
        }

        foreach ($courses as $index => $course) {
            if (empty($course['image'])) {
                $seed = $course['title'] ?? $index;
                $courses[$index]['image'] = $this->pick_placeholder_image($courseplaceholders, $seed);
            }
        }

        $logourl = $this->page->theme->setting_file_url('logo', 'logo');
        if (empty($logourl)) {
            $corelogo = $this->get_logo_url();
            $logourl = $corelogo ? $corelogo->out(false) : false;
        }

        $toplinks = [
            [
                'label' => 'Pour les personnes',
                'url' => (new moodle_url('/local/spacechildpages/people.php'))->out(false),
            ],
            [
                'label' => 'Pour les universites',
                'url' => (new moodle_url('/local/spacechildpages/universities.php'))->out(false),
            ],
            [
                'label' => 'Pour les gouvernements',
                'url' => (new moodle_url('/local/spacechildpages/governments.php'))->out(false),
            ],
        ];

        $businessurl = (new moodle_url('/local/spacechildpages/business.php'))->out(false);

        $carousel = [
            [
                'primary' => [
                    'tag' => 'Pour les equipes',
                    'title' => 'Formez votre equipe aux standards qualite',
                    'text' => 'Parcours certifiants, suivi et reporting pour vos managers.',
                    'cta' => 'Voir l\'offre equipes',
                    'url' => $businessurl,
                    'badge' => 'Jusqu\'a 50% pour les equipes',
                ],
                'secondary' => [
                    'tag' => 'Carriere',
                    'title' => 'Commencer, changer ou evoluer',
                    'text' => 'Des parcours clairs et des projets pour progresser.',
                    'cta' => 'S\'inscrire gratuitement',
                    'url' => (new moodle_url('/login/signup.php'))->out(false),
                    'icon' => $CFG->wwwroot . '/theme/spacechild/pix/landing/course-ai.svg',
                ],
            ],
            [
                'primary' => [
                    'tag' => 'Nouveaute',
                    'title' => 'Programme ISO 9001, de l\'audit a l\'amelioration',
                    'text' => '6 semaines, ateliers pratiques et certificat final.',
                    'cta' => 'Voir le programme',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'iso 9001']))->out(false),
                    'badge' => 'Demarrage immediat',
                ],
                'secondary' => [
                    'tag' => 'HSE',
                    'title' => 'Securite au travail et prevention',
                    'text' => 'Modules courts et outils concrets pour vos equipes.',
                    'cta' => 'Explorer les cours',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'hse']))->out(false),
                    'icon' => $CFG->wwwroot . '/theme/spacechild/pix/landing/course-agile.svg',
                ],
            ],
            [
                'primary' => [
                    'tag' => 'Qualite',
                    'title' => 'Mettez en place un systeme de management solide',
                    'text' => 'Documentation, audits internes et pilotage des actions.',
                    'cta' => 'Decouvrir le parcours',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'qualite']))->out(false),
                    'badge' => 'Parcours certifiant',
                ],
                'secondary' => [
                    'tag' => 'Processus',
                    'title' => 'Cartographier et optimiser vos flux',
                    'text' => 'Outils concrets pour gagner en performance.',
                    'cta' => 'Voir les cours',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'processus']))->out(false),
                    'icon' => $CFG->wwwroot . '/theme/spacechild/pix/landing/course-cloud.svg',
                ],
            ],
            [
                'primary' => [
                    'tag' => 'Leadership',
                    'title' => 'Pilotez le changement avec les bons indicateurs',
                    'text' => 'KPI, reporting et rituels pour engager vos equipes.',
                    'cta' => 'Explorer le catalogue',
                    'url' => (new moodle_url('/course/index.php'))->out(false),
                    'badge' => 'Formations managers',
                ],
                'secondary' => [
                    'tag' => 'Risques',
                    'title' => 'Identifier et maitriser les risques',
                    'text' => 'Methodes d\'evaluation et plans d\'actions.',
                    'cta' => 'Voir les cours',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'risques']))->out(false),
                    'icon' => $CFG->wwwroot . '/theme/spacechild/pix/landing/course-product.svg',
                ],
            ],
        ];

        $carouseldots = [];
        foreach ($carousel as $index => $slide) {
            $carouseldots[] = [
                'isactive' => $index === 0,
            ];
        }

        $context = [
            'wwwroot' => $CFG->wwwroot,
            'hastoplinks' => !empty($toplinks),
            'toplinks' => $toplinks,
            'logourl' => $logourl,
            'sitename' => format_string(
                $SITE->shortname ?: $SITE->fullname,
                true,
                ['context' => context_course::instance(SITEID), 'escape' => false]
            ),
            'sitefullname' => format_string(
                $SITE->fullname,
                true,
                ['context' => context_course::instance(SITEID), 'escape' => false]
            ),
            'exploreurl' => (new moodle_url('/course/index.php'))->out(false),
            'searchurl' => (new moodle_url('/course/search.php'))->out(false),
            'loginurl' => (new moodle_url('/login/index.php'))->out(false),
            'signupurl' => (new moodle_url('/login/signup.php'))->out(false),
            'statlearners' => format_float(
                $DB->count_records_select('user', 'deleted = 0 AND suspended = 0'),
            0),
            'statcourses' => format_float(
                $DB->count_records_select('course', 'id <> :siteid', ['siteid' => SITEID]),
            0),
            'statpartners' => format_float(
                $DB->count_records_select('course_categories', 'visible = 1'),
            0),
            'hascategories' => !empty($categories),
            'categories' => $categories,
            'hascourses' => !empty($courses),
            'courses' => $courses,
            'hascarousel' => !empty($carousel),
            'carousel' => $carousel,
            'carouselcount' => count($carousel),
            'carouseldots' => $carouseldots,
        ];

        return $this->render_from_template('theme_spacechild/frontpage', $context);
    }

    /**
     * Get a limited list of frontpage categories.
     *
     * @param int $limit
     * @return array
     */
    protected function get_frontpage_categories(int $limit): array {
        $items = [];
        $categories = core_course_category::top()->get_children();

        foreach ($categories as $category) {
            if (!$category->is_uservisible()) {
                continue;
            }

            $items[] = [
                'name' => $category->get_formatted_name(),
                'url' => $category->get_view_link()->out(false),
                'image' => $this->pick_placeholder_image($this->category_placeholder_images(), $category->id),
            ];

            if (count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    /**
     * Get a limited list of frontpage courses.
     *
     * @param int $limit
     * @return array
     */
    protected function get_frontpage_courses(int $limit): array {
        $items = [];
        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->set_courses_display_options([
            'recursive' => true,
            'limit' => $limit + 1,
        ]);

        $courses = core_course_category::top()->get_courses($chelper->get_courses_display_options());

        foreach ($courses as $course) {
            if ($course->id == SITEID) {
                continue;
            }

            $summary = format_text($course->summary, FORMAT_HTML, ['noclean' => true]);
            $summary = shorten_text(trim(strip_tags($summary)), 140);
            $image = $this->get_course_overview_image_url($course);
            if (empty($image)) {
                $image = $this->pick_placeholder_image($this->course_placeholder_images(), $course->id);
            }

            $items[] = [
                'title' => format_string($course->fullname),
                'summary' => $summary,
                'image' => $image,
                'url' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
            ];

            if (count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    /**
     * Pick a deterministic placeholder image from the provided list.
     *
     * @param array $images
     * @param string|int $seed
     * @return string
     */
    protected function pick_placeholder_image(array $images, $seed): string {
        if (empty($images)) {
            return '';
        }

        $hash = sprintf('%u', crc32((string) $seed));
        $index = (int) ($hash % count($images));
        return $this->image_url($images[$index], 'theme_spacechild')->out(false);
    }

    /**
     * Return a course overview image URL when available.
     *
     * @param \core_course_list_element $course
     * @return string|null
     */
    protected function get_course_overview_image_url(\core_course_list_element $course): ?string {
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                return moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
            }
        }

        return null;
    }

    /**
     * Placeholder image keys for category cards.
     *
     * @return array
     */
    protected function category_placeholder_images(): array {
        return [
            'landing/category-data',
            'landing/category-business',
            'landing/category-cloud',
            'landing/category-design',
            'landing/category-marketing',
            'landing/category-cyber',
            'landing/category-office',
            'landing/category-languages',
        ];
    }

    /**
     * Placeholder image keys for course cards.
     *
     * @return array
     */
    protected function course_placeholder_images(): array {
        return [
            'landing/course-python',
            'landing/course-product',
            'landing/course-ai',
            'landing/course-cloud',
            'landing/course-ux',
            'landing/course-agile',
        ];
    }
}
