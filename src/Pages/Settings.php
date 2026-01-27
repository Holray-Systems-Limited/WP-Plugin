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
        $this->title = __('Settings & Sync','holray-units');
        $this->menu_title = __('Settings & Sync','holray-units');

        parent::__construct();
    }
    

    /**
     * Render the sync page
     * 
     * @return void
     */
    public function render()
    {

        $holray_url = esc_url(Plugin::getOption('holray_url',''));
        $api_key = esc_attr(Plugin::getOption('api_key',''));
        $api_caching  = (int) Plugin::getOption('api_caching', 1);

        $sym = Plugin::getOption('currency_symbol', '£');
        $pos = Plugin::getOption('currency_position', 'left');
        $tsep = Plugin::getOption('thousand_sep', ',');
        $dsep = Plugin::getOption('decimal_sep', '.');
        $decs = (int) Plugin::getOption('decimals', 2);


        $excluded_locations = Plugin::getOption('excluded_locations', []);

        $search_results_page_id = Plugin::getOption('search_results_page','0');

        $last_sync = null;
        if(Plugin::getOption('last_unit_sync', false)) {
            $last_sync = date("jS M Y \\a\\t h:ia", Plugin::getOption('last_unit_sync', time()));
        }
        
        include_once HOLRAY_UNITS_PATH . "src/views/admin/settings.php";
    }

}