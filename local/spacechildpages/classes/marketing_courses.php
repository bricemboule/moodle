<?php
namespace local_spacechildpages;

defined('MOODLE_INTERNAL') || die();

use context_course;
use core_course_category;
use coursecat_helper;
use moodle_url;

class marketing_courses {
    /**
     * Get marketing courses for templates.
     *
     * @param int $limit
     * @return array
     */
    public static function get_courses(int $limit = 0): array {
        global $CFG, $USER;

        require_once($CFG->dirroot . '/course/renderer.php');
        require_once($CFG->libdir . '/enrollib.php');

        $items = [];
        $chelper = new coursecat_helper();
        $displayoptions = ['recursive' => true];

        if ($limit > 0) {
            $displayoptions['limit'] = $limit + 1;
        }

        $chelper->set_show_courses(\core_course_renderer::COURSECAT_SHOW_COURSES_EXPANDED)
            ->set_courses_display_options($displayoptions);
        $courses = core_course_category::top()->get_courses($chelper->get_courses_display_options());

        $placeholders = self::course_placeholder_images();

        foreach ($courses as $course) {
            if ($course->id == SITEID) {
                continue;
            }

            $summary = format_text($course->summary, FORMAT_HTML, ['noclean' => true]);
            $summary = shorten_text(trim(strip_tags($summary)), 140);
            $image = self::get_course_overview_image_url($course);

            if (empty($image)) {
                $image = self::pick_placeholder_image($placeholders, $course->id);
            }

            $canrequest = true;
            if (isloggedin() && !isguestuser()) {
                $context = context_course::instance($course->id);
                $canrequest = !is_enrolled($context, $USER->id, '', true);
            }

            $items[] = [
                'title' => format_string($course->fullname),
                'summary' => $summary,
                'image' => $image,
                'url' => (new moodle_url('/local/spacechildpages/course_detail.php', ['courseid' => $course->id]))->out(false),
                'enrolurl' => (new moodle_url('/local/spacechildpages/enrol_request.php', ['courseid' => $course->id]))->out(false),
                'canrequest' => $canrequest,
            ];

            if ($limit > 0 && count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    /**
     * Return a course overview image URL when available.
     *
     * @param \core_course_list_element $course
     * @return string|null
     */
    protected static function get_course_overview_image_url(\core_course_list_element $course): ?string {
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
     * Pick a deterministic placeholder image from the provided list.
     *
     * @param array $images
     * @param string|int $seed
     * @return string|null
     */
    protected static function pick_placeholder_image(array $images, $seed): ?string {
        global $OUTPUT;

        if (empty($images) || empty($OUTPUT)) {
            return null;
        }

        $hash = sprintf('%u', crc32((string) $seed));
        $index = (int) ($hash % count($images));
        return $OUTPUT->image_url($images[$index], 'theme_spacechild')->out(false);
    }

    /**
     * Placeholder image keys for course cards.
     *
     * @return array
     */
    protected static function course_placeholder_images(): array {
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
