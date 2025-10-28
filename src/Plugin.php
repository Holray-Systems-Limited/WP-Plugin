<?php
namespace Holray\Plugin;

use Holray\Plugin\Actions\SaveApiSettings;
use Holray\Plugin\Actions\SavePricingSettings;
use Holray\Plugin\Actions\SaveSearchResultsSettings;
use Holray\Plugin\Actions\Sync;
use Holray\Plugin\Actions\Wp\UpdateUnit;
use Holray\Plugin\Metabox\UnitFields;
use Holray\Plugin\Pages\Settings;
use Holray\Plugin\PostTypes\Unit;
use Holray\Plugin\Shortcodes\HolraySearch;
use Holray\Plugin\Util\Api;
use Holray\Plugin\Util\Templates;

class Plugin {

    /**
     * An array of all available actions for this plugin
     * 
     * @var array<string, \Holray\Plugin\Actions\Action>
     */
    private $actions = [];

    /**
     * The active API Instance for Holray
     * 
     * @var \Holray\Plugin\Util\Api
     */
    private $api;

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

        $this->init_pages();
        $this->init_posttypes();
        $this->init_taxonomies();
        $this->init_actions();
        $this->init_metaboxes();
        $this->init_shortcodes();

        // Add the filter for our custom settings variables
        add_filter("query_vars", [ $this, "init_custom_query_vars" ]);

        // Add the post state indicators to pages in wp-admin
        add_filter("display_post_states", [ $this, "add_post_states" ], 10, 2);

        // Setup the api instance.
        $this->api = new Api(self::getOption("holray_url"), self::getOption("api_key"));

        // Add the filters for loading custom templates
        Templates::init_loader();
        
        // Got to add support for custom post type featured images
        add_theme_support('post-thumbnails');

        // Enqueue css and scripts required
        add_action("admin_enqueue_scripts", [ $this, "init_admin_css" ]);
        add_action("wp_enqueue_scripts", [ $this, "init_frontend_css" ]);
        add_action("wp_enqueue_scripts", [ $this, "init_frontend_js" ]);
    }

    /**
     * Load Pages
     */
    private function init_pages()
    {
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
        Unit::register_location_taxonomy();
        // Unit::register_feature_taxonomy();
    }

    /**
     * Load the actions
     */
    private function init_actions()
    {
        $this->actions["save_unit_fields"] = new UpdateUnit;
        $this->actions["save_api_settings"] = new SaveApiSettings;
        $this->actions["save_pricing_settings"] = new SavePricingSettings;
        $this->actions["save_search_results_settings"] = new SaveSearchResultsSettings;

        $this->actions["sync_with_holray"] = new Sync;
    }

    /**
     * Init the metaboxes
     */
    private function init_metaboxes()
    {
        new UnitFields;
    }

    /**
     * Init the available shortcodes
     */
    private function init_shortcodes()
    {
        new HolraySearch;
    }

    /**
     * Init the post states (adds a custom indicator next to each page)
     */
    public function add_post_states($post_states, $post)
    {
        $page_id = intval(Plugin::getOption("search_results_page", "0"));
        if($post->ID == $page_id) {
            $post_states["holray_results_page"] = __("Holray Search Results", "holray_units");
        }

        return $post_states;
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
    public static function getOption(string $name, $default_value = '')
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

    /**
     * Get the API instance for the plugin
     * 
     * @return \Holray\Plugin\Util\Api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Load the admin CSS
     */
    public function init_admin_css()
    {
        wp_enqueue_style( 'holray_admin_css', HOLRAY_UNITS_URL . '/src/css/admin.css', false, HOLRAY_UNITS_VERSION );
    }

    /**
     * Load the frontend (single and archive) CSS
     */
    public function init_frontend_css()
    {
        wp_enqueue_style( 'holray_css', HOLRAY_UNITS_URL . '/src/css/holray.css', false, HOLRAY_UNITS_VERSION );
    }

    /**
     * Load the frontend (search form) JS
     */
    public function init_frontend_js()
    {
        wp_enqueue_script( 'holray_js', HOLRAY_UNITS_URL . '/src/js/holray.js', false, HOLRAY_UNITS_VERSION, true );
    }
    
}