<?php

namespace Holray\Plugin\Util;

use Holray\Plugin\Plugin;

class Templates
{

    /**
     * Init the template filters
     */
    public static function init_loader()
    {
        add_filter('template_include', '\Holray\Plugin\Util\Templates::wp_template_include', 10, 1);
    }

    /**
     * The WordPress filter the handles which template should be loaded
     * based on the page we're loading
     * 
     * @return string
     */
    public static function wp_template_include($template)
    {

        $tpl = $template;
        if(is_singular('holray_unit')) {
            $tpl = self::locate_template("single-holray_unit.php");
        }

        if(is_post_type_archive('holray_unit')) {
            $tpl = self::locate_template("archive-holray_unit.php");
        }

        // Load our custom template for search results too.
        if(get_the_ID() == Plugin::getOption("search_results_page", "0")) {
            $tpl = self::locate_template("holray-search-results.php");
        }

        // If (for whatever reason) the template doesn't exist in our plugin.. use the WordPress default to prevent a white screen.
        if($tpl !== '') {
            return $tpl;
        }

        return $template;
    }

    /**
     * Locate a Holray template and return the path of the template
     * that should be loaded
     * 
     * @return string
     */
    public static function locate_template(string $name)
    {
        $theme_path  = trailingslashit(get_stylesheet_directory()) . 'holray/' . $name;
        if (file_exists($theme_path)) return $theme_path;

        $plugin_path = HOLRAY_UNITS_PATH . 'templates/' . $name;
        if (file_exists($plugin_path)) return $plugin_path;

        return '';
    }
}
