<?php

namespace Holray\Plugin\Services;

class UpdateService
{

    /**
     * The service initialisation
     */
    public function __construct()
    {

        add_filter('update_plugins_holray-systems-limited.github.io', [$this, 'check_for_updates'], 10, 3);
    }

    function check_for_updates( $update, $plugin_data, $plugin_file ){
        
        static $response = false;
        
        if( empty( $plugin_data['UpdateURI'] ) || ! empty( $update ) )
            return $update;
        
        if( $response === false )
            $response = wp_remote_get( $plugin_data['UpdateURI'] );
        
        if( empty( $response['body'] ) )
            return $update;
        
        $custom_plugins_data = json_decode( $response['body'], true );
        
        if( ! empty( $custom_plugins_data[ $plugin_file ] ) )
            return $custom_plugins_data[ $plugin_file ];
        else
            return $update;
        
    }
}
