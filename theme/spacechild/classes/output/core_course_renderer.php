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

namespace theme_spacechild\output;

use core_course_category;
use coursecat_helper;
use moodle_url;
use context_course;

/**
 * Course renderer overrides.
 *
 * @package   theme_spacechild
 * @copyright 2022 - 2023 Marcin Czaja (https://rosea.io)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_course_renderer extends \core_course_renderer {

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

        $categories = $this->get_frontpage_categories(8);
        $courses = $this->get_frontpage_courses(8);

        $logourl = $this->page->theme->setting_file_url('logo', 'logo');
        if (empty($logourl)) {
            $corelogo = $this->get_logo_url();
            $logourl = $corelogo ? $corelogo->out(false) : false;
        }

        $context = [
            'wwwroot' => $CFG->wwwroot,
            'currentyear' => date('Y'),
            'hastoplinks' => false,
            'toplinks' => [],
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
            'supporturl' => (new moodle_url('/user/contactsitesupport.php'))->out(false),
            'peopleurl' => (new moodle_url('/local/spacechildpages/people.php'))->out(false),
            'businessurl' => (new moodle_url('/local/spacechildpages/business.php'))->out(false),
            'universitiesurl' => (new moodle_url('/local/spacechildpages/universities.php'))->out(false),
            'governmentsurl' => (new moodle_url('/local/spacechildpages/governments.php'))->out(false),
            'haspaths' => true,
            'paths' => [
                [
                    'badge' => 'Populaire',
                    'title' => 'Parcours Data Analyst',
                    'summary' => 'Analyse, visualisation et storytelling data.',
                    'duration' => '8 semaines',
                    'level' => 'Débutant',
                    'projects' => '6 projets',
                    'cta' => 'Voir le parcours',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'data']))->out(false),
                ],
                [
                    'badge' => 'Carrière',
                    'title' => 'Product Management',
                    'summary' => 'Stratégie, discovery et delivery produit.',
                    'duration' => '10 semaines',
                    'level' => 'Intermédiaire',
                    'projects' => '4 cas pratiques',
                    'cta' => 'Découvrir',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'product']))->out(false),
                ],
                [
                    'badge' => 'Certifiant',
                    'title' => 'Fondamentaux Cloud',
                    'summary' => 'Services clés, sécurité et déploiement.',
                    'duration' => '6 semaines',
                    'level' => 'Débutant',
                    'projects' => 'Labs guidés',
                    'cta' => 'Explorer',
                    'url' => (new moodle_url('/course/search.php', ['search' => 'cloud']))->out(false),
                ],
            ],
            'hasoutcomes' => true,
            'outcomes' => [
                [
                    'value' => '92%',
                    'label' => 'Satisfaction moyenne',
                ],
                [
                    'value' => '4.8/5',
                    'label' => 'Note des cours',
                ],
                [
                    'value' => '78%',
                    'label' => 'Taux de complétion',
                ],
            ],
            'hastestimonials' => true,
            'testimonials' => [
                [
                    'quote' => 'Parcours clair et progressif, j’ai pu changer de poste en 3 mois.',
                    'name' => 'Amina S.',
                    'role' => 'Data Analyst',
                ],
                [
                    'quote' => 'Les projets pratiques m’ont aidé à constituer un vrai portfolio.',
                    'name' => 'David K.',
                    'role' => 'Product Manager',
                ],
                [
                    'quote' => 'Contenu à jour et support réactif, très bonne expérience.',
                    'name' => 'Marta L.',
                    'role' => 'Ingénieure Cloud',
                ],
            ],
            'hasfaq' => true,
            'faq' => [
                [
                    'question' => 'Puis-je apprendre à mon rythme ?',
                    'answer' => 'Oui, les modules sont accessibles 24/7 et adaptés à votre agenda.',
                ],
                [
                    'question' => 'Y a-t-il des projets pratiques ?',
                    'answer' => 'Chaque parcours inclut des exercices guidés et des projets concrets.',
                ],
                [
                    'question' => 'Obtiens-je un certificat ?',
                    'answer' => 'Une attestation est délivrée à la fin des parcours complétés.',
                ],
            ],
            'statlearners' => format_float(
                $DB->count_records_select('user', 'deleted = 0 AND suspended = 0')
            , 0),
            'statcourses' => format_float(
                $DB->count_records_select('course', 'id <> :siteid', ['siteid' => SITEID])
            , 0),
            'statpartners' => format_float(
                $DB->count_records_select('course_categories', 'visible = 1')
            , 0),
            'hascategories' => !empty($categories),
            'categories' => $categories,
            'hascourses' => !empty($courses),
            'courses' => $courses,
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
            if (!$category->is_visible()) {
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
        global $CFG;

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
