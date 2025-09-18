<?php
namespace Holray\Plugin\Actions;

use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Request;

class SaveLegacySettings extends Action
{

    /**
     * The action name
     * 
     * @var string
     */
    public $action = "save_legacy_settings";

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
        if(Request::has("legacy_api_url")) {
            Plugin::setOption('legacy_api_url', Request::inputUrl('legacy_api_url', ''));
        }
        return $this->redirect( $this->base_url . '&holray-message=legacy-success' );
    }
}