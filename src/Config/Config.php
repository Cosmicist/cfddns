<?php namespace Flatline\CfDdns\Config;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

class Config implements ConfigInterface, \ArrayAccess
{
    protected $config_filename;

    protected $locator;

    protected $resolver;

    protected $loader;

    protected $items;

    protected $loaded = false;


    public function __construct(
        $config_filename,
        FileLocatorInterface $locator
      , LoaderResolverInterface $resolver
    )
    {
        $this->config_filename = $config_filename;
        $this->locator = $locator;
        $this->resolver = $resolver;
    }

    public function load()
    {
        // Locate the config file wherever it may be
        try {
            $resource = $this->locator->locate($this->config_filename, getcwd(), true);

            // Add the Yml loader to the resolver
            $this->resolver->addLoader(new Loader\YmlLoader($this->locator));

            // Try to resolve the locator resource
            if ($loader = $this->resolver->resolve($resource)) {
                // Load the found config file resource
                $this->items = $loader->load($resource);

                // Mark as loaded
                $this->loaded = true;

                return true;
            }
        } catch(\InvalidArgumentException $ex) {}

        return false;
    }

    public function loaded()
    {
        return $this->loaded;
    }

    /**
     * Get the config array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        $default = microtime(true);

        return $this->get($key, $default) !== $default;
    }

    /**
     * Get a config item using "dot" notation.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_null($key)) return $this->items;

        return array_get($this->items, $key, $default);
    }

    /**
     * Set a config item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return Config
     */
    public function set($key, $value)
    {
        array_set($this->items, $key, $value);

        return $this;
    }

    /**
     * Unset a config item using "dot" notation.
     *
     * @param  string  $key
     * @return Config
     */
    public function remove($key)
    {
        array_unset($this->items, $key);

        return $this;
    }

    /* ArrayAccess */

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->remove($key);
    }
}
