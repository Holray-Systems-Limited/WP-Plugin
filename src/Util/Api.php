<?php

namespace Holray\Plugin\Util;

use Holray\Plugin\Exceptions\HolrayException;

class Api
{

    /**
     * The base URL to use
     * 
     * @var string
     */
    private $url;


    /**
     * The API Key to use
     * 
     * @var string
     */
    private $key;


    /**
     * New instance
     */
    public function __construct($url, $key)
    {
        if(is_string($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            $this->url = $this->build_api_url($url);
        }
        $this->key = $key;
    }

    /**
     * Parse and build a url
     */
    private function build_api_url(string $url)
    {
        $parsed = parse_url($url);

        if($parsed === false || is_null($parsed)) {
            throw new HolrayException("Invalid Holray URL passed.");
        }

        return "https://" . ( isset($parsed["user"]) && isset($parsed["pass"]) ? $parsed["user"] . ":" . $parsed["pass"] . "@" : "" ) . $parsed["host"] . "/public/api.php";
    }

    /**
     * Handle a WordPress query response
     */
    private function handle_response(array|\WP_Error $response)
    {
        if(is_wp_error($response)) {
            return $response;
        }

        $responseCode = wp_remote_retrieve_response_code($response);
        if($responseCode < 200 || $responseCode > 299) {
            return new \WP_Error('holray_api_http_code', sprintf(__('Holray API error code (HTTP %s).', 'holray-units'), $responseCode));
        }
        
        $json = json_decode(wp_remote_retrieve_body($response));
        if(is_null($json)) {
            return new \WP_Error('holray_api_response', __('Invalid JSON was returned from the API.', 'holray-units'), $json);
        }
        if(isset($json->status) && strtolower($json->status) !== "success") {
            return new \WP_Error('holray_api_status', isset($json->message[0]) ? $json->message[0] : __('Unknown API response', 'holray-units'), $json);
        }
        
        return $json;
    }

    /**
     * Get the base API URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    /**
     * Make a post request to the Holray API
     */
    public function post(string $service, array $data = [])
    {
        $base = [ 
            "apikey" => $this->key,
            "service" => $service
        ];
        $body = array_merge($data, $base);

        $response = wp_remote_post($this->url, [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            "body" => wp_json_encode($body),
        ]);

        return $this->handle_response($response);
    }

    /**
     * Make a GET request to the Holray API
     */
    public function get(string $service, array $data = []) {

        $base = [ 
            "apikey" => $this->key,
            "service" => $service
        ];
        $body = array_merge($data, $base);

        $response = wp_remote_get($this->url, [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            "body" => $body,
        ]);

        return $this->handle_response($response);

    }
    
}
