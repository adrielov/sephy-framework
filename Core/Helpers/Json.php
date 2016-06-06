<?php
/**
 * User: Sephy
 * Date: 06/06/2016
 * Time: 04:23.
 */
namespace Core\Helpers;

class Json
{
    /**
     * @param      $json
     * @param bool $group
     *
     * @return string
     */
    public static function encode($json, $group = false)
    {
        $jsonSafed = [];
        if ($group) {
            $jsonSafed[$group] = $json;
        } else {
            $jsonSafed = $json;
        }

        return json_encode($jsonSafed, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_PRETTY_PRINT);
    }
}
