<?php

namespace Holray\Plugin\PostTypes;

class Unit
{
    /**
     * Init the testimonial post type
     */
    function __construct()
    {
        //
    }

    /**
     * Register the post type
     * 
     * @return void
     */
    public static function register_post_type()
    {
        $labels = array(
            'name' => __('Units', 'holray-units'),
            'singular_name' => __('Unit', 'holray-units'),
            'name_admin_bar' => __('Units', 'holray-units'),
            'menu_name' => __('Holray Units', 'holray-units'),
            'add_new' => __('Add New', 'holray-units'),
            'add_new_item' => __('Add New Unit', 'holray-units'),
            'edit_item' => __('Edit Unit', 'holray-units'),
            'new_item' => __('New Unit', 'holray-units'),
            'view_item' => __('View Unit', 'holray-units'),
            'search_items' => __('Search Units', 'holray-units'),
            'not_found' => __('No units found', 'holray-units'),
            'not_found_in_trash' => __('No units found in Trash', 'holray-units'),
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-admin-multisite',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'thumbnail'],
            'rewrite' => ['slug' => 'units', 'with_front' => false],
        );
        register_post_type('holray_unit', $args);

        add_filter("manage_holray_unit_posts_columns", "\Holray\Plugin\PostTypes\Unit::add_custom_edit_columns");
        add_filter("manage_holray_unit_posts_custom_column", "\Holray\Plugin\PostTypes\Unit::add_custom_edit_columns_data", 10, 2);
    }

    /**
     * Post type admin area column filter
     */
    public static function add_custom_edit_columns($columns)
    {
        unset($columns['author']);
        $columns['title'] = __('Unit', 'holray-units');
        $new_cols = [];
        foreach ($columns as $key => $val) {
            $new_cols[$key] = $val;
            if ($key === "cb") { // cb = "Checkbox"
                $new_cols['class'] = __('Unit Class', 'holray-units');
            }
        }
        return $new_cols;
    }

    /**
     * Add the data into our custom columns
     */
    public static function add_custom_edit_columns_data($column, $post_id)
    {
        switch ($column) {
            case 'class':
                echo "<strong> <a href='". get_edit_post_link($post_id) ."' class='row-title'>" . esc_html(get_post_meta($post_id, 'holray_class', true)) . '</a></strong>';
                break;
        }
    }

    /**
     * Register post type custom features taxonomy
     * 
     * @return void
     */
    public static function register_feature_taxonomy()
    {
        $labels = array(
            'name' => __('Unit Features', 'holray-units'),
            'singular_name' => __('Unit Features', 'holray-units'),
        );
        register_taxonomy('holray_unit_feature', 'holray_unit', [
            'labels' => $labels,
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'unit-features'],
        ]);
    }

    /**
     * Register post type custom location taxonomy
     * 
     * @return void
     */
    public static function register_location_taxonomy()
    {
        $labels = array(
            'name' => __('Unit Locations', 'holray-units'),
            'singular_name' => __('Unit Location', 'holray-units'),
        );
        register_taxonomy('holray_unit_location', 'holray_unit', [
            'labels' => $labels,
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'unit-location'],
        ]);
    }
}