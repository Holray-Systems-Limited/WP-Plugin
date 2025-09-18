<?php
namespace Holray\Plugin\Pages;

use Holray\Plugin\Plugin;

/**
 * Pages base page class
 */

class Settings extends Page
{

    /**
     * The parent slug to the menu item
     * 
     * @var string
     */
    public $parent_slug = "edit.php?post_type=holray_unit";

    /**
     * The pages slug
     * 
     * @var string
     */
    public $slug = "holray-settings";

    /**
     * The permissions for the user to access this page.
     * 
     * @var string
     */
    public $capability = "manage_options";


    /**
     * On new page created
     * 
     * @return void
     */
    public function __construct()
    {
        $this->title = __('Settings','holray-units');
        $this->menu_title = __('Settings','holray-units');

        parent::__construct();
    }
    

    /**
     * Render the sync page
     * 
     * @return void
     */
    public function render()
    {

        $api_url = esc_url(Plugin::getOption('legacy_api_url',''));
        $api_base = esc_url(Plugin::getOption('api_base',''));
        $api_key = esc_attr(Plugin::getOption('api_key',''));
        $checkout = esc_url(Plugin::getOption('checkout_base',''));
        $enforce  = (int) Plugin::getOption('enforce_online_cta', 1);
        $sym = Plugin::getOption('currency_symbol', '£');
        $pos = Plugin::getOption('currency_position', 'left');
        $tsep = Plugin::getOption('thousand_sep', ',');
        $dsep = Plugin::getOption('decimal_sep', '.');
        $decs = (int) Plugin::getOption('decimals', 2);
        
        include_once HOLRAY_UNITS_PATH . "src/views/admin/settings.php";
    }

}