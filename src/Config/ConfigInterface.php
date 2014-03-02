<?php namespace Flatline\CfDdns\Config;

interface ConfigInterface
{
    /**
     * Load config
     *
     * @return ConfigInterface
     */
    public function load();

    /**
     * Check if the config was loaded
     *
     * @return ConfigInterface
     */
    public function loaded();

    /**
     * Get the config array
     *
     * @return array
     */
    public function toArray();

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key);

    /**
     * Get a config item using "dot" notation.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $value = null);

    /**
     * Set a config item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return ConfigInterface
     */
    public function set($key, $value);

    /**
     * Unset a config item using "dot" notation.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return ConfigInterface
     */
    public function remove($key);
}
