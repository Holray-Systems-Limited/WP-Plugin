<?php
namespace Holray\Plugin\Actions;

use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Request;

class SavePricingSettings extends Action
{

    /**
     * The action name
     * 
     * @var string
     */
    public $action = "save_pricing_settings";

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
        if(Request::has("currency_symbol")) {
            Plugin::setOption('currency_symbol', Request::input('currency_symbol', '£'));
        }
        if(Request::has("currency_position")) {
            Plugin::setOption('currency_position', Request::input('currency_position', 'left'));
        }
        if(Request::has("thousand_sep")) {
            Plugin::setOption('thousand_sep', Request::input('thousand_sep', ','));
        }
        if(Request::has("decimal_sep")) {
            Plugin::setOption('decimal_sep', Request::input('decimal_sep', '.'));
        }
        if(Request::has("decimals")) {
            Plugin::setOption('decimals', Request::input('decimals', '2'));
        }

        return $this->redirect( $this->base_url . '&holray-message=pricing-success' );
    }
}