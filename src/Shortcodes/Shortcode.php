<?php

namespace Holray\Plugin\Shortcodes;

class Shortcode
{

    /**
     * The shortcode
     */
    public $shortcode = "";

    /**
     * Shortcode arguments
     * 
     * @var array
     */
    public $args = [];

    /**
     * New instance of the class
     * 
     * @return \Holray\Plugin\Shortcodes\Shortcode
     */
    public function __construct()
    {
        $this->register_shortcode();
    }
    
    /**
     * Register shortcode within WordPress
     * 
     * @return void
     */
    private function register_shortcode() {
        add_shortcode($this->shortcode, array($this, 'handle_shortcode'));
    }

    /**
     * Handle shortcode
     * 
     * @param  array $atts
     * @return void
     */
    public function handle_shortcode($atts) {
        $this->args = $atts;
        return $this->render();
    }

    /**
     * Render the shortcode
     * 
     * @return string
     */
    public function render() {
        return '';
    }

}