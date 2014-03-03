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
     * Save config
     *
     * @param $path
     * @return bool
     */
    public function save($path);

    /**
     * Get the config array
     *
     * @return array
     */
    public function toArray();

    /**
     * Set/Replace config items
     *
     * @param array $items
     * @return ConfigInterface
     */
    public function items(array $items);

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
     * @param  mixed   $value
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
     * @return ConfigInterface
     */
    public function remove($key);

    /**
     * Get the config filename
     *
     * @return string
     */
    public function getFilename();

    /**
     * Set the config filename
     *
     * @param string $config_filename
     * @return ConfigInterface
     */
    public function setFilename($config_filename);
}
