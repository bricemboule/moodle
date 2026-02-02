<?php
defined('MOODLE_INTERNAL') || die();

/**
 * File serving for local_spacechildpages.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function local_spacechildpages_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel !== CONTEXT_SYSTEM) {
        return false;
    }

    if ($filearea !== 'marketingcategoryimage') {
        return false;
    }

    $itemid = array_shift($args);
    if ($itemid === null) {
        return false;
    }

    $filename = array_pop($args);
    if ($filename === null) {
        return false;
    }

    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_spacechildpages', $filearea, $itemid, $filepath, $filename);
    if (!$file || $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}
