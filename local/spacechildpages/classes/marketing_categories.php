<?php
namespace local_spacechildpages;

defined('MOODLE_INTERNAL') || die();

use context_coursecat;
use core_course_category;
use moodle_url;

class marketing_categories {
    /**
     * Get marketing categories for templates.
     *
     * @param int $limit
     * @return array
     */
    public static function get_categories(int $limit = 0): array {
        $items = [];
        $categories = core_course_category::top()->get_children();
        $placeholders = self::category_placeholder_images();

        foreach ($categories as $category) {
            if (!$category->is_uservisible()) {
                continue;
            }

            $count = $category->get_courses_count(['recursive' => true]);
            $meta = $count === 0 ? get_string('nocourses') : self::format_course_count($count);

            $image = self::get_category_image_url($category);
            if (empty($image)) {
                $image = self::pick_placeholder_image($placeholders, $category->id);
            }

            $items[] = [
                'name' => $category->get_formatted_name(),
                'meta' => $meta,
                'image' => $image,
                'url' => $category->get_view_link()->out(false),
            ];

            if ($limit > 0 && count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    /**
     * Format a course count for category metadata.
     *
     * @param int $count
     * @return string
     */
    protected static function format_course_count(int $count): string {
        $label = $count === 1 ? get_string('course') : get_string('courses');
        return format_float($count, 0) . ' ' . $label;
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
     * Get the category image URL if it exists.
     *
     * @param core_course_category $category
     * @return string|null
     */
    protected static function get_category_image_url(core_course_category $category): ?string {
        $context = context_coursecat::instance($category->id);
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $context->id,
            'coursecat',
            'categoryimage',
            0,
            'itemid, filepath, filename',
            false
        );

        if (empty($files)) {
            return null;
        }

        $file = reset($files);
        return moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        )->out(false);
    }

    /**
     * Placeholder image keys for category cards.
     *
     * @return array
     */
    protected static function category_placeholder_images(): array {
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
}
