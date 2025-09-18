<?php
namespace Holray\Plugin;

use Holray\Plugin\Actions\SaveApiSettings;
use Holray\Plugin\Actions\SaveLegacySettings;
use Holray\Plugin\Actions\SavePricingSettings;
use Holray\Plugin\Pages\Settings;
use Holray\Plugin\PostTypes\Unit;

class Plugin {

    /**
     * An array of all available actions for this plugin
     * 
     * @var array<string, \Holray\Plugin\Actions\Action>
     */
    private $actions = [];

    /**
     * New plugin instance
     */
    public function __construct()
    {
        add_action("init", [ $this, "init" ]);
    }
    
    /**
     * Initialise the plugin
     */
    public function init()
    {
        // Load the language file.
        load_plugin_textdomain('holray-units', false, HOLRAY_UNITS_PATH . '/languages');

        $this->init_posttypes();
        $this->init_taxonomies();
        $this->init_actions();

        add_filter("query_vars", [ $this, "init_custom_query_vars" ]);

        new Settings;
    }

    /**
     * Load post types
     */
    private function init_posttypes()
    {
        Unit::register_post_type();
    }

    /**
     * Load taxonomies
     */
    private function init_taxonomies()
    {
        Unit::register_category_taxonomy();
    }

    /**
     * Load the actions
     */
    private function init_actions()
    {
        $this->actions["save_api_settings"] = new SaveApiSettings;
        $this->actions["save_pricing_settings"] = new SavePricingSettings;
        $this->actions["save_legacy_settings"] = new SaveLegacySettings;
    }

    /**
     * Get the actions list
     * 
     * @return array<string, \Holray\Plugin\Actions\Action>
     */
    public function getActions()
    {
        return $this->actions;
    }
    
    /**
     * Init the allow admin query arguments (handles fail and success messages)
     * 
     * @return array
     */
    public function init_custom_query_vars($vars)
    {
        $vars[] = "holray-message";
        return $vars;
    }

    /**
     * Get the plugin instance
     * 
     * @return \Holray\Plugin\Plugin
     */
    public static function getInstance()
    {
        global $holray_plugin;
        return $holray_plugin;
    }

    /**
     * Get a plugin option
     * 
     * @return mixed
     */
    public static function getOption(string $name, $default_value)
    {
        return get_option('holray_units_' . $name, $default_value);
    }

    /**
     * Set a plugin option
     * 
     * @return mixed
     */
    public static function setOption(string $name, $value)
    {
        return update_option('holray_units_' . $name, $value);
    }
    
}