<?php
namespace Holray\Plugin\Services;

use Holray\Plugin\Plugin;

class UnitService {


    /**
     * Sync existing and new locations with Holray to the WP install.
     */
    public static function sync_with_holray()
    {

        do_action( 'holray_units_before_sync', Plugin::getInstance()->getApi()->getUrl() );

        $response = Plugin::getInstance()->getApi()->post("units", [
            "Web" => 1,
            'allunits' => 1,
        ]);

        $units = apply_filters('holray_units_items', $response->data, Plugin::getInstance()->getApi()->getUrl());

  
        $wp_features = get_terms([
            'taxonomy' => 'holray_unit_feature',
            'hide_empty' => false,
        ]);

        /**
         * An array of the holray property to post_meta mappings
         * so we can easily loop through and update/add them
         */
        $unit_to_meta = [
            "class" => "class",
            "minberth" => "min_berth",
            "maxberth" => "max_berth",
            "layout" => "layout",
            "maxpets" => "max_pets",
            "minnights" => "min_nights",
            "weburl" => "external_booking_url",
        ];

        foreach($units as $raw_unit) {
            $holray_unit = apply_filters( 'holray_units_item', $raw_unit );

            if($holray_unit->status !== "ACTIVE") {
                continue;
            }

            $existing_units = new \WP_Query([
                "post_type" => 'holray_unit',
                'posts_per_page' => 1,
                'meta_query' => [
                    [
                        'key' => 'holray_external_id',
                        'value' => $holray_unit->id,
                        'compare' => '='
                    ]
                ]
            ]);

            // Find or create the unit in WP from Holray.
            if($existing_units->have_posts()) {
                $wp_unit = $existing_units->posts[0];
            } else {
                $new_post_id = wp_insert_post([
                    'post_title' => $holray_unit->unit,
                    'post_content' => $holray_unit->unitdesc,
                    'post_status' => 'publish',
                    'post_type' => 'holray_unit',
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                ]);
                $wp_unit = get_post($new_post_id);
                add_post_meta($wp_unit->ID, 'holray_external_id', $holray_unit->id, true);
            }

            $locations = LocationService::find_from_holray_id($holray_unit->location);
            if(is_array($locations) && count($locations) > 0) {
                wp_set_post_terms($wp_unit->ID, [ $locations[0]->term_id ], 'holray_unit_location');
            }

            // Import unit image ID
            if(isset($holray_unit->imgurl) && $holray_unit->imgurl !== "") {

                /**
                 * Track the image downloads so we don't download images if we don't
                 * have to on every sync
                 */
                $existing_unit_uploaded_url = get_post_meta($wp_unit->ID, 'holray_img_url', true);
                if($existing_unit_uploaded_url !== $holray_unit->imgurl) {
                    if($existing_unit_uploaded_url == '') { // Image never downloaded.
                        add_post_meta($wp_unit->ID, 'holray_img_url', esc_url_raw($holray_unit->imgurl));
                    } else {
                        update_post_meta($wp_unit->ID, 'holray_img_url', esc_url_raw($holray_unit->imgurl));
                    }
                    $unit_image_id = media_sideload_image(esc_url_raw($holray_unit->imgurl), $wp_unit->ID, null, 'id');
                    if(!is_wp_error($unit_image_id)) {
                        set_post_thumbnail($wp_unit, $unit_image_id);
                    }
                }

            }

            // Import the meta properties set at $unit_to_meta 
            foreach($unit_to_meta as $holray_property => $meta_key) {
                if(get_post_meta($wp_unit->ID, 'holray_' . $meta_key, true) == '') {
                    add_post_meta($wp_unit->ID, 'holray_' . $meta_key, $holray_unit->$holray_property, true);
                } else {
                    update_post_meta($wp_unit->ID, 'holray_' . $meta_key, $holray_unit->$holray_property);
                }
            }
        }

        do_action( 'holray_units_after_sync', $response->data );
    }

}