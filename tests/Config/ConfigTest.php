<?php namespace Flatline\CfDdns\Config;

use Mockery as m;

class ConfigTest extends \TestCase
{
    /**
     * @var m\Mock
     */
    protected $locator;

    /**
     * @var m\Mock
     */
    protected $resolver;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var String
     */
    protected $configFilename;

    public function setUp()
    {
        $this->configFilename = "test.yml";

        $this->locator = m::mock('Symfony\Component\Config\FileLocatorInterface');

        $this->resolver = m::mock('Symfony\Component\Config\Loader\LoaderResolverInterface');

        $this->config = new Config($this->configFilename, $this->locator, $this->resolver);
    }

    public function testLoadConfig()
    {
        $this->locator->shouldReceive('locate')->once()->withArgs([$this->configFilename]);
        $this->config->load();
    }
} 
