<?php

namespace Holray\Plugin\Shortcodes;

use Holray\Plugin\Plugin;

class HolrayCalendar extends Shortcode
{

    /**
     * The shortcode
     */
    public $shortcode = "holray_calendar";


    /**
     * Render the shortcode
     * 
     * @return string
     */
    public function render() {
        wp_enqueue_script( 'holray_calendars_js' );
        ob_start();

        $calendar_id = md5($this->getArgs()["unit"]);
        
        include_once HOLRAY_UNITS_PATH . "src/views/shortcodes/calendar.php";
        $contents = ob_get_clean();
        return $contents;
    }

    /**
     * Handle default args if they're not set
     */
    private function getArgs()
    {
        $args = $this->args;
        $defaults = [
            "unit" => "Unit"
        ];

        return array_merge($defaults, $args);
    }

}