<?php
namespace Holray\Plugin\Actions;

use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Cache;
use Holray\Plugin\Util\Request;

class SaveSearchResultsSettings extends Action
{

    /**
     * The action name
     * 
     * @var string
     */
    public $action = "save_search_results_settings";

    /**
     * The base page url
     * 
     * @var string
     */
    public $base_url = "/wp-admin/edit.php?post_type=holray_unit&page=holray-settings";

    /**
     * Handle the action
     * 
     * @return void
     */
    public function handle() {
        if(Request::has("search_results_page")) {
            Plugin::setOption('search_results_page', Request::input('search_results_page', '0'));
        }

        if(Request::has("exclude_locations") && is_array(Request::input("exclude_locations"))) {
            Cache::forget("search_valid_locations");
            Plugin::setOption('excluded_locations', $this->getExcludedLocations());
        }


        return $this->redirect( $this->base_url . '&holray-message=search-success' );
    }


    /**
     * Safely store the exclude locations array
     */
    private function getExcludedLocations()
    {
        $raw = Request::input("exclude_locations");
        $safe = [];
        foreach($raw as $val) {
            $intVal = intval($val);
            if($intVal === 0) continue;
            $safe[] = $intVal;
        }


        return $safe;
    }
}