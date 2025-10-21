<?php
namespace Holray\Plugin\Metabox;

class UnitFields extends Metabox {

    /**
     * The metabox id
     */
    public $id = 'holray-unit-fields-box';

    /**
     * The metabox title
     */
    public $title = 'Unit Details';

    /**
     * The metabox post type
     */
    public $posttype = 'holray_unit';


    /**
     * Handle the rendering of the metabox
     */
    public function render($post)
    {

        $class = get_post_meta($post->ID, 'holray_class', true);
        $min_berth = get_post_meta($post->ID, 'holray_min_berth', true);
        $max_berth = get_post_meta($post->ID, 'holray_max_berth', true);
        $layout = get_post_meta($post->ID, 'holray_layout', true);
        $max_pets = get_post_meta($post->ID, 'holray_max_pets', true);
        $min_nights = get_post_meta($post->ID, 'holray_min_nights', true);
        $external_booking_url = get_post_meta($post->ID, 'holray_external_booking_url', true);

        include_once HOLRAY_UNITS_PATH . "src/views/admin/meta/unit-meta.php";
    }

}