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
        if(Request::has("api_base")) {
            Plugin::setOption('api_base', Request::inputUrl('api_base', 'https://YOUR.holray.co.uk'));
        }
        if(Request::has("api_key")) {
            Plugin::setOption('api_key', Request::input('api_key', ''));
        }
        if(Request::has("checkout_base")) {
            Plugin::setOption('checkout_base', Request::input('checkout_base', ''));
        }
        return $this->redirect( $this->base_url . '&holray-message=api-success' );
    }
}