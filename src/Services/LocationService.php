<?php

namespace Holray\Plugin\Services;

use Holray\Plugin\Exceptions\HolrayImportException;
use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Cache;

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

                if ($wp_location === false || is_wp_error($wp_location)) {

                    if(is_wp_error($wp_location)) {
                        if(in_array("term_exists", array_keys($wp_location->errors))) {
                            // It's a location with the same name?
                            // Retry adding it.
                            $wp_location = wp_insert_term($holray_location->location . "-" . $holray_location->id, 'holray_unit_location');
                        }
                    }

                    // The retry failed?
                    if ($wp_location === false || is_wp_error($wp_location)) {
                        if (is_wp_error($wp_location)) {
                            throw new HolrayImportException( implode(", ", $wp_location->get_error_messages()) );
                        } else {
                            throw new HolrayImportException('Failed to import location, an error occured when updating external id : ' . $holray_location);
                        }
                    }
                }

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

    /**
     * Get a list of WordPress location terms that are allowed to be shown based on the filters
     */
    public static function get_allowed_locations()
    {

        /**
         * Remember the valid locations for a day to save looping and fetching the locations
         * list every request!
         * 
         * Cache is cleared when we save the search settings!
         */
        return Cache::rememberIgnore("search_valid_locations", 60 * 24, function() {
            $locations = get_terms([ "taxonomy" => "holray_unit_location", "hide_empty" => false ]);
            $excluded_locations = Plugin::getOption('excluded_locations', []);
            $valid_locations = [];
    
            // Apply the filter to allowed display locations
            foreach($locations as $loc)
            {
                $location_external_id = get_term_meta($loc->term_id, 'holray_external_id', true);
                if(!in_array($location_external_id, $excluded_locations)) {
                    $valid_locations[] = $loc;
                }
            }
    
            return $valid_locations;
        });

    }


    /**
     * Get a list of allowed location term id's that are allowed
     */
    public static function get_allowed_location_ids()
    {
        return array_map(function($item) {
            return $item->term_id;
        }, self::get_allowed_locations());
    }
}
