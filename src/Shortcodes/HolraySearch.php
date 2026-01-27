<?php

namespace Holray\Plugin\Shortcodes;

use Holray\Plugin\Plugin;
use Holray\Plugin\Services\LocationService;
use Holray\Plugin\Services\SearchResultsService;
use Holray\Plugin\Util\Cache;

class HolraySearch extends Shortcode
{

    /**
     * The shortcode
     */
    public $shortcode = "holray_search";


    /**
     * Render the shortcode
     * 
     * @return string
     */
    public function render() {
        ob_start();
        $args = $this->getArgs();
        $locations = LocationService::get_allowed_locations();

        // Cache our features list for 60 mins.
        $features = Cache::remember("features", 24 * 60, function() {
            return Plugin::getInstance()->getApi()->get("features");
        });
        if(is_wp_error($features)) {
            $features = [];
        }
        
        $values = SearchResultsService::get_search_values();

        include_once HOLRAY_UNITS_PATH . "src/views/shortcodes/search.php";
        $contents = ob_get_clean();
        return $contents;
    }

    /**
     * Handle default args if they're not set
     */
    private function getArgs()
    {
        $args = $this->args;
        $defaults = [
            "placement" => "topbar",
            "results_page" => get_permalink(Plugin::getOption("search_results_page", "0")),
        ];

        return array_merge($defaults, $args);
    }

}