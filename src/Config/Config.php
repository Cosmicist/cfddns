<?php namespace Flatline\CfDdns\Config;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Yaml\Dumper;

class Config implements ConfigInterface, \ArrayAccess
{
    protected $items;

    protected $loaded = false;

    /**
     * @var
     */
    private $config_filename;

    /**
     * @var \Symfony\Component\Config\FileLocatorInterface
     */
    private $locator;

    /**
     * @var \Symfony\Component\Config\Loader\LoaderResolverInterface
     */
    private $resolver;

    /**
     * @var \Symfony\Component\Yaml\Dumper
     */
    private $dumper;


    public function __construct(
        $config_filename,
        FileLocatorInterface $locator
      , LoaderResolverInterface $resolver
      , Dumper $dumper
    )
    {
        $this->config_filename = $config_filename;
        $this->locator = $locator;
        $this->resolver = $resolver;
        $this->dumper = $dumper;
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

    public function save($path)
    {
        if ($yml = $this->dumper->dump($this->items, 5)) {
            file_put_contents("$path/{$this->config_filename}", $yml);
            return true;
        }
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
     * Set/Replace config items
     *
     * @param array $items
     * @return $this
     */
    public function items(array $items)
    {
        $this->items = $items;

        return $this;
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

    /**
     * Get the config filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->config_filename;
    }

    /**
     * Set the config filename
     *
     * @param string $config_filename
     * @return $this
     */
    public function setFilename($config_filename)
    {
        $this->config_filename = basename($config_filename);

        return $this;
    }
}
