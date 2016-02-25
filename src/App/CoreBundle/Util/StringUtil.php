<?php

namespace App\CoreBundle\Util;

class StringUtil
{
    /**
     * @param $value
     * @return string
     */
    public static function camelCaseToSnakeCase($value)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $value, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    /**
     * @param $value
     * @return string
     */
    public static function snakeCaseToCamelCase($value)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
    }

    /**
     * @param $value
     * @param int $length
     * @param bool $preserve
     * @param string $separator
     * @return string
     */
    public static function truncate($value, $length = 30, $preserve = false, $separator = '...')
    {
        if (mb_strlen($value) > $length) {
            if ($preserve) {
                // If breakpoint is on the last word, return the value without separator.
                if (false === ($breakpoint = mb_strpos($value, ' ', $length))) {
                    return $value;
                }

                $length = $breakpoint;
            }

            return rtrim(mb_substr($value, 0, $length)) . $separator;
        }
        return $value;
    }
}