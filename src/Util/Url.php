<?php

namespace Holray\Plugin\Util;

use Holray\Plugin\Plugin;

class Url
{
    /**
     * Generate a customer booking URL
     */
    public static function getCustBookUrl()
    {
        $api_url_parts = parse_url(Plugin::getOption("holray_url", "https://holray.co.uk"));
        return $api_url_parts["scheme"] . "://" . $api_url_parts["host"] . "/public/custbook1.php";
    }
}