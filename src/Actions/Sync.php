<?php
namespace Holray\Plugin\Actions;

use Holray\Plugin\Plugin;
use Holray\Plugin\Services\LocationService;
use Holray\Plugin\Services\UnitService;
use Holray\Plugin\Util\Request;

class Sync extends Action
{

    /**
     * The action name
     * 
     * @var string
     */
    public $action = "sync_with_holray";

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
        $url = Plugin::getOption("holray_url");
        $key = Plugin::getOption("api_key");
        if($url == '' || $key == '') {
            $this->redirect( $this->base_url . '&error=sync-not-setup' );
            return;
        }

        LocationService::sync_with_holray();
        UnitService::sync_with_holray();
        Plugin::setOption('last_unit_sync', time());
        $this->redirect( $this->base_url . '&error=sync-success' );
    }
}