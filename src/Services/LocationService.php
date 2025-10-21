<?php

namespace Holray\Plugin\Services;

use Holray\Plugin\Exceptions\HolrayImportException;
use Holray\Plugin\Plugin;

class LocationService
{


    /**
     * Sync existing and new locations with Holray to the WP install.
     */
    public static function sync_with_holray()
    {

        $response = Plugin::getInstance()->getApi()->post("locations", [
            "Web" => 1,
        ]);

        $locations = $response->data;

        foreach ($locations as $holray_location) {

            $wp_location = get_terms([
                'taxonomy' => 'holray_unit_location',
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key' => 'holray_external_id',
                        'value' => $holray_location->id,
                        'compare' => '='
                    ]
                ]
            ]);


            if (count($wp_location) > 0) {
                $wp_location = ["term_id" => $wp_location[0]->term_id];
            } else {
                $wp_location = wp_insert_term($holray_location->location, 'holray_unit_location');
                $meta = add_term_meta($wp_location["term_id"], 'holray_external_id', $holray_location->id);

                if ($meta === false || is_wp_error($meta)) {
                    // Clean up term since it's not linked externally.
                    wp_delete_term($wp_location["term_id"], 'holray_unit_location');
                    if (is_wp_error($meta)) {
                        throw new HolrayImportException( implode(", ", $meta->get_error_messages()) );
                    } else {
                        
                        throw new HolrayImportException('Failed to import location, an error occured when updating external id : ' . $holray_location);
                    }
                }
            }

            /**
             * No data from Holray is required for locations for now
             * But we can update term metas here if we need too.
             */
        }
    }


    /**
     * Find a location from holray ID
     * 
     * @return WP_Term[]|int[]|string[]|string|WP_Error
     */
    public static function find_from_holray_id($holray_id)
    {
        $wp_location = get_terms([
            'taxonomy' => 'holray_unit_location',
            'hide_empty' => false,
            'meta_query' => [
                [
                    'key' => 'holray_external_id',
                    'value' => $holray_id,
                    'compare' => '='
                ]
            ]
        ]);

        return $wp_location;
    }
}
