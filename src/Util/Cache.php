<?php

namespace Holray\Plugin\Util;

use Holray\Plugin\Exceptions\HolrayException;
use Holray\Plugin\Plugin;

class Cache
{
    
    /**
     * Get or set the cache for a set amount of time
     * 
     * @param string $key The cache key
     * @param float|int $duration  The amount of time to cache the response for in minutes
     * @param Callable $fn The function which should return the data to be cached.
     */
    public static function remember(
        string $key,
        $duration,
        Callable $fn
    ) {

        // Caching if we can
        if(Plugin::getOption("api_caching", true)) {
            return self::rememberIgnore($key, $duration, $fn);
        }
        // We're never caching and always hitting the API
        return $fn();
    }

    /**
     * Get or set the cache but we don't respect the plugin settings and always cache!
     * 
     * @param string $key The cache key
     * @param float|int $duration  The amount of time to cache the response for in minutes
     * @param Callable $fn The function which should return the data to be cached.
     */
    public static function rememberIgnore (
        string $key,
        $duration,
        Callable $fn
    ) {

        $full_key = "holray_" . $key;
        $data = get_transient($full_key);

        if($data !== false) {
            return $data;
        }

        $cachable = $fn();
        if(is_wp_error($cachable)) {
            throw new HolrayException("Cannot cache " . $key . " because it threw an error.");
            return [];
        }
        $mins = $duration * 60;
        set_transient( $full_key, $cachable, $mins);


        return $cachable;
    }

}
