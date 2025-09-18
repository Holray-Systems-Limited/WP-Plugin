<?php
namespace Holray\Plugin\Util;

class Request {

    /**
     * Get the Holray value prefix
     */
    private static $prefix = "holray_units_";

    /**
     * Get if a Holray Input exists
     */
    public static function has(string $name)
    {
        return isset($_REQUEST[self::$prefix . $name]);
    }

    /**
     * Get a holray value from the current request
     */
    public static function input(string $name, $default = null)
    {
        if(self::has($name)) {
            return $_REQUEST[self::$prefix . $name];
        }

        return $default;
    }

    /**
     * Get an escaped value as a URL.
     */
    public static function inputUrl(string $name, $default = '')
    {
        return esc_url_raw(self::input($name, $default));
    }

}