<?php

namespace Holray\Plugin\Util;

use Holray\Plugin\Plugin;

class Unit
{
    
    /**
     * Build a book now link from a result class
     */
    public static function bookNowLinkFromResult($holrayResult)
    {
        $base_url = Plugin::getOption("holray_url") . "/public/custbook1.php";
        $query = http_build_query([
            "unitid" => $holrayResult->unit->id,
            "startdate" => $holrayResult->fromdt,
            "enddate" => $holrayResult->todt,
        ]);

        return esc_html($base_url . "?" . $query);
    }

}
