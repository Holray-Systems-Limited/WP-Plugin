<?php
namespace Holray\Plugin\Actions;

use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Request;

class SaveApiSettings extends Action
{

    /**
     * The action name
     * 
     * @var string
     */
    public $action = "save_api_settings";

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
        if(Request::has("holray_url")) {
            Plugin::setOption('holray_url', Request::inputUrl('holray_url', 'https://YOUR.holray.co.uk'));
        }
        if(Request::has("api_key")) {
            Plugin::setOption('api_key', Request::input('api_key', ''));
        }
        if(Request::has("api_caching")) {
            Plugin::setOption('api_caching', 1);
        } else {
            Plugin::setOption('api_caching', 0);
        }

        
        return $this->redirect( $this->base_url . '&holray-message=api-success' );
    }
}