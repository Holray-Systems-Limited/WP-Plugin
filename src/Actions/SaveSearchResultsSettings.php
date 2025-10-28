<?php
namespace Holray\Plugin\Actions;

use Holray\Plugin\Plugin;
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
        return $this->redirect( $this->base_url . '&holray-message=search-success' );
    }
}