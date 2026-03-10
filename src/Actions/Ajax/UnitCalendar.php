<?php
namespace Holray\Plugin\Actions\Ajax;

use Holray\Plugin\Actions\Action;
use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Request;

class UnitCalendar extends Action
{

    /**
     * The action name
     * 
     * @var string
     */
    public $action = "holrayunits_unit_calendar";

    /**
     * Is an WP Ajax action
     * 
     * @var boolean
     */
    public $is_ajax = true;

    /**
     * Is a public WP Ajax action
     * 
     * @var boolean
     */
    public $is_public_ajax = true;
    

    /**
     * Handle the action
     * 
     * @return void
     */
    public function handle() {

        $api_url_parts = parse_url(Plugin::getOption("holray_url"));
        $built_url = $api_url_parts["scheme"] . "://" . $api_url_parts["host"] . "/public/proxy.php";
        
        $query_string = http_build_query([
            "uclass" => $this->input("unit", "UNIT NAME"),
            "numcals" => 1,
            "duration" => $this->input("duration", 7),
            "berths" => $this->input("berths", 2),
            "numpets" => $this->input("pets", 0),
            "seldt" => $this->dateInput("selDate")->format("d/m/Y"),
            "startdt" => $this->dateInput("startDate")->format("d/m/Y"),
            "tgt" => $this->input("target", "holray-cal"),
            "url" => $api_url_parts["scheme"] . "://" . $api_url_parts["host"] . "/public"
        ]);
        // echo "<pre>";
        // var_dump($query_string);
        // echo "</pre>";

        $built_url = $built_url . "?" . $query_string;

        $response = wp_remote_get($built_url);
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            echo $response["body"];
        } else {
            echo "<div>Failed to fetch the unit calendar, please try later</div>";
        }

        wp_die();
    }

    /**
     * A small helper to get an input value
     */
    private function input(string $name, $default = null)
    {
        if(array_key_exists($name, $_GET)) return $_GET[$name];

        return $default;
    }

    
    /**
     * A small helper to get a date value or return now.
     */
    private function dateInput(string $name, $default = 'now')
    {
        if(array_key_exists($name, $_GET) && \DateTime::createFromFormat("d/m/Y", $_GET[$name]) !== FALSE) return \DateTime::createFromFormat("d/m/Y", $_GET[$name]);

        return new \DateTime($default);
    }
}