<?php
/**
 * Plugin Name: Holray Units
 * Description: Catalogue plugin: creates a "holray_unit" custom post type with templates, a Sync & Settings page, catalogue and availability shortcodes.
 * Version: 1.3.1
 * Author: You
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: holray-units
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) { exit; }
define('HOLRAY_UNITS_VERSION', '1.3.1');
define('HOLRAY_UNITS_PATH', plugin_dir_path(__FILE__));
define('HOLRAY_UNITS_URL', plugin_dir_url(__FILE__));

// Load translations
add_action('init', function () {
    load_plugin_textdomain('holray-units', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

function holray_units_register_types() {
    $labels = [
        'name' => __('Units', 'holray-units'),
        'singular_name' => __('Unit', 'holray-units'),
        'menu_name' => __('Units', 'holray-units'),
        'add_new' => __('Add New', 'holray-units'),
        'add_new_item' => __('Add New Unit', 'holray-units'),
        'edit_item' => __('Edit Unit', 'holray-units'),
        'new_item' => __('New Unit', 'holray-units'),
        'view_item' => __('View Unit', 'holray-units'),
        'search_items' => __('Search Units', 'holray-units'),
        'not_found' => __('No units found', 'holray-units'),
        'not_found_in_trash' => __('No units found in Trash', 'holray-units'),
    ];
    register_post_type('holray_unit', [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-admin-multisite',
        'supports' => ['title','editor','thumbnail','excerpt'],
        'rewrite' => ['slug' => 'units', 'with_front' => false],
    ]);
    register_taxonomy('unit_location', 'holray_unit', [
        'labels' => [
            'name' => __('Unit Locations', 'holray-units'),
            'singular_name' => __('Unit Location', 'holray-units'),
        ],
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'unit-location'],
    ]);
}
add_action('init', 'holray_units_register_types');

function holray_units_activate() { holray_units_register_types(); flush_rewrite_rules(); }
register_activation_hook(__FILE__, 'holray_units_activate');
function holray_units_deactivate() { flush_rewrite_rules(); }
register_deactivation_hook(__FILE__, 'holray_units_deactivate');

function holray_units_enqueue_assets() {
    wp_enqueue_style('holray-units', HOLRAY_UNITS_URL . 'assets/css/holray-units.css', [], HOLRAY_UNITS_VERSION);
}
add_action('wp_enqueue_scripts', 'holray_units_enqueue_assets');

function holray_units_shortcode_catalog($atts) {
    $atts = shortcode_atts(['location' => '', 'per_page' => 12], $atts, 'holray_catalog');
    $tax_query = [];
    if (!empty($atts['location'])) {
        $tax_query[] = ['taxonomy' => 'unit_location','field' => 'slug','terms' => sanitize_title($atts['location'])];
    }
    $q = new WP_Query(['post_type' => 'holray_unit','posts_per_page' => intval($atts['per_page']),'tax_query' => !empty($tax_query) ? $tax_query : null]);
    ob_start();
    if ($q->have_posts()) : ?>
        <div class="holray-unit-grid">
            <?php while ($q->have_posts()) : $q->the_post(); ?>
                <article class="holray-unit-card">
                    <a href="<?php echo esc_url(get_permalink()); ?>">
                        <?php if (has_post_thumbnail()) the_post_thumbnail('medium'); ?>
                        <h3><?php echo esc_html(get_the_title()); ?></h3>
                        <p class="holray-unit-class"><?php echo esc_html(get_post_meta(get_the_ID(), 'class', true)); ?></p>
                    </a>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php else: ?>
        <p><?php esc_html_e('No results.', 'holray-units'); ?></p>
    <?php endif;
    return ob_get_clean();
}
add_shortcode('holray_catalog', 'holray_units_shortcode_catalog');

require_once HOLRAY_UNITS_PATH . 'includes/template-loader.php';
require_once HOLRAY_UNITS_PATH . 'includes/sync.php';
require_once HOLRAY_UNITS_PATH . 'includes/api.php';
require_once HOLRAY_UNITS_PATH . 'includes/utils.php';
require_once HOLRAY_UNITS_PATH . 'includes/search.php';
