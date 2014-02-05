<?php

/**
 * Helpers by Laravel Framework - http://laravel.com
 * I included these so the library can be used outside Laravel.
 */

if ( ! function_exists('dd'))
{
    /**
     * Dump the passed variables and end the script.
     *
     * @param  dynamic  mixed
     * @return void
     */
    function dd()
    {
        array_map(function($x) { var_dump($x); }, func_get_args()); die;
    }
}

if ( ! function_exists('array_set'))
{
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_set(&$array, $key, $value)
    {
        if (is_null($key)) return $array = $value;

        $keys = explode('.', $key);

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if ( ! isset($array[$key]) || ! is_array($array[$key]))
            {
                $array[$key] = array();
            }

            $array =& $array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}

if ( ! function_exists('array_get'))
{
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) return $array;

        if (isset($array[$key])) return $array[$key];

        foreach (explode('.', $key) as $segment)
        {
            if ( ! is_array($array) || ! array_key_exists($segment, $array))
            {
                return $default instanceof Closure ? $default() : $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }
}

/**
 * Unset an array item using "dot" notation.
 *
 * @author Luciano Longo <luciano.longo@studioignis.net>
 * @param  array   $array
 * @param  string  $key
 * @return array
 */
function array_unset(&$array, $key)
{
    $keys = explode('.', $key);

    while (count($keys) > 1)
    {
        $key = array_shift($keys);

        // If the key doesn't exist at this depth just return, since there's
        // nothing to unset.
        if ( ! isset($array[$key]) || ! is_array($array[$key]))
        {
            return;
        }

        $array =& $array[$key];
    }

    unset($array[array_shift($keys)]);

    return $array;
}
