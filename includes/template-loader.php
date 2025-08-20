<?php
if (!defined('ABSPATH')) { exit; }
function holray_units_template_include($template) {
    if (is_singular('holray_unit')) {
        $t = holray_units_locate_template('single-holray_unit.php');
        if ($t) return $t;
    }
    if (is_post_type_archive('holray_unit')) {
        $t = holray_units_locate_template('archive-holray_unit.php');
        if ($t) return $t;
    }
    return $template;
}
add_filter('template_include', 'holray_units_template_include');
function holray_units_locate_template($filename) {
    $theme_path  = trailingslashit(get_stylesheet_directory()) . 'holray/' . $filename;
    if (file_exists($theme_path)) return $theme_path;
    $plugin_path = HOLRAY_UNITS_PATH . 'templates/' . $filename;
    if (file_exists($plugin_path)) return $plugin_path;
    return '';
}
