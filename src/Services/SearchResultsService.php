<?php

namespace Holray\Plugin\Services;

use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Cache;

class SearchResultsService
{

    /**
     * Init the post states on the search results page
     */
    public static function do_search()
    {
        // Validate our request
        $fields = self::validate();
        if(is_wp_error($fields)) {
            return [
                "fields" => [],
                "units" => [],
                "wpUnits" => [],
                "hasError" => true,
                "errors" => $fields->get_error_messages()
            ];
        }

        // Allow for fields to be changed using filters
        $fields = apply_filters("holray_search_query_args", $fields);


        $search_hash = self::getSearchHash($fields);
        // Actually search using our fields
        $units = Cache::remember("holray_search_" . $search_hash, 5, function() use($fields) {
            return Plugin::getInstance()->getApi()->post("availability", [
                "Web" => 1,
                "allunits" => 1,
                "features" => $fields["features"],
                "fromdt" => $fields["fromdt"],
                "nights" => $fields["nights"],
                "flexibility" => $fields["flex"],
                "location" => $fields["location"],
                "minparty" => $fields["partysize"],
                "maxparty" => $fields["partysize"],
            ]);
        });

        // Filter and only include available units
        $units = array_filter($units->data->results, function ($unit) {
            return $unit->available && $unit->avonline;
        });

        $external_ids = array_map(fn ($unit) => intval($unit->unit->id), $units);
        // Units key paired to their holray ids and WP Post object
        $wpUnits = self::getWpUnits($external_ids);

        // Filter and only include units that we have a WP Post for
        $units = array_filter($units, function($unit) use($wpUnits) {
            return isset($wpUnits[$unit->unit->id]);
        });

        // Build an array for each unit containing the API object, meta object and the WP Post object
        $builtUnits = array_map(function($unit) use($wpUnits) {
            $wpUnit = $wpUnits[$unit->unit->id];

            $meta = [
                "class" => get_post_meta($wpUnit->ID, "holray_class", true),
                "min_berth" => get_post_meta($wpUnit->ID, 'holray_min_berth', true),
                "max_berth" => get_post_meta($wpUnit->ID, 'holray_max_berth', true),
                "max_pets" => get_post_meta($wpUnit->ID, 'holray_max_pets', true),
            ];

            $meta = apply_filters( "holray_results_card_meta", $meta, $wpUnit, $unit );

            return [
                "api" => $unit,
                "meta" => $meta,
                "wp_unit" => $wpUnit
            ];
        }, $units);

        return [
            "fields" => $fields,
            "units" => $builtUnits,
            "hasError" => false,
            "errors" => []
        ];

    }

    /**
     * Get the search fields
     */
    public static function get_search_values()
    {
        $data = [
            "location" => 0,
            "partysize" => "",
            "features" => [],
            "fromdt" => "",
            "nights" => "",
            "flex" => null,
        ];

        // Location 
        if(isset($_GET["location"]) && intval($_GET["location"]) > 0) {
            $data["location"] = intval($_GET["location"]);
        }

        // Party size 
        if(isset($_GET["partysize"]) && intval($_GET["partysize"]) > 0) {
            $data["partysize"] = intval($_GET["partysize"]);
        }

        // Features 
        if(isset($_GET["features"])) {
            if(!is_array($_GET["features"])) {
                if(intval($_GET["features"]) !== 0) {
                    $data["features"] = [ intval($_GET["features"]) ];
                }
            } else {
                $data["features"] = array_map(fn ($feature) => intval($feature), $_GET["features"]);
            }
        }
        
        // From date 
        if(isset($_GET["fromdt"])) {
            $fromDate = \DateTime::createFromFormat("Y-m-d", $_GET["fromdt"]);
            $today = (new \DateTime)->setTime(0, 0, 0, 0);
            if($fromDate != false) {
                $fromDate->setTime(0, 0, 0, 0);
                if($fromDate->getTimestamp() >= $today->getTimestamp()) {
                    $data["fromdt"] = $fromDate->format("Y-m-d");
                }
            }
        }

        // Nights 
        if(isset($_GET["nights"]) && intval($_GET["nights"]) > 0) {
            $data["nights"] = intval($_GET["nights"]);
        }

        // Flexible dates
        if(isset($_GET["flex"])) {
            $data["flex"] = intval($_GET["flex"]);
        }

        return $data;

    }

    /**
     * Validate the arguments are added.
     */
    private static function validate()
    {

        $values = self::get_search_values();

        if($values["location"] === 0) {
            return new \WP_Error("holray_results_error", "Invalid location selected.");
        }

        if($values["partysize"] == "" || $values["partysize"] == 0) {
            return new \WP_Error("holray_results_error", "Invalid partysize selected.");
        }

        if($values["fromdt"] == "") {
            return new \WP_Error("holray_results_error", "Please enter a start date.");
        }

        if($values["nights"] == "" || $values["nights"] == 0) {
            return new \WP_Error("holray_results_error", "The number of nights is invalid.");
        }

        return $values;
    }

    /**
     * Get the unique search hash based on the fields entered
     */
    private static function getSearchHash(array $fields) {
        $string = "";
        foreach($fields as $key => $val) {
            if(is_array($val)) {
                $string .= $key . "=" . implode(",", $val) . "&";
            } else {
                $string .= $key . "=" . $val . "&";
            }
        }

        return md5($string);
    }

    /**
     * Fetch the WordPress unit posts for all available posts that are key paired to the external ID and WP Post
     * 
     * @return array<int, \WP_Post>
     */
    private static function getWpUnits(array $holray_ids)
    {
        $wpUnits = new \WP_Query([
            "posts_per_page" => -1,
            "post_type" => "holray_unit",
            "meta_query" => [
                [
                    "key" => "holray_external_id",
                    "value" => $holray_ids,
                    "compare" => "IN"
                ]
            ]
        ]);

        $data = [];
        foreach($wpUnits->posts as $unit) {
            $holray_id = get_post_meta($unit->ID, "holray_external_id", true);

            $data[$holray_id] = $unit;
        }

        return $data;

    }

}