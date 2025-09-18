<?php
namespace Holray\Plugin\PostTypes;

class Unit {
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
            'menu_name' => __('Units', 'holray-units'),
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
            'supports' => ['title','editor','thumbnail','excerpt'],
            'rewrite' => ['slug' => 'units', 'with_front' => false],
        );
        register_post_type( 'holray_unit', $args );
    }

    /**
     * Register post type custom category
     * 
     * @return void
     */
    public static function register_category_taxonomy()
    {
        $labels = array(
            'name' => __('Unit Locations', 'holray-units'),
            'singular_name' => __('Unit Location', 'holray-units'),
        );
        register_taxonomy('unit_location', 'holray_unit', [
            'labels' => $labels,
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'unit-location'],
        ]);
    }
}