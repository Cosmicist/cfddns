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

        $loader = m::mock('Flatline\CfDdns\Config\Loader\YmlLoader');
        $loader->shouldReceive('load')->andReturn([
            'foo' => [
                'bar' => 'baz'
            ]
        ]);

        $this->locator
            ->shouldReceive('locate')
            ->once()
            ->withArgs([$this->configFilename, getcwd(), true])
            ->andReturn($this->configFilename);

        $this->resolver
            ->shouldReceive('addLoader')->once();
        $this->resolver
            ->shouldReceive('resolve')->once()->andReturn($loader);

        $this->config->load();
    }

    public function testLoadConfig()
    {
        $this->assertTrue($this->config->loaded());
        $this->assertTrue($this->config->has('foo'));
        $this->assertTrue($this->config->has('foo.bar'));
        $this->assertEquals(['bar' => 'baz'], $this->config->get('foo'));
        $this->assertEquals('baz', $this->config->get('foo.bar'));
    }

    public function testSetConfig()
    {
        $this->config->set('asd', 'qwe');

        $this->assertTrue($this->config->has('asd'));
        $this->assertEquals('qwe', $this->config['asd']);
    }

    public function testUnsetConfig()
    {
        unset($this->config['foo.bar']);

        $this->assertTrue($this->config->has('foo'));
        $this->assertFalse($this->config->has('foo.bar'));
    }

    public function testToArray()
    {
        $expected = [
            'foo' => [
                'bar' => 'baz'
            ]
        ];

        $this->assertEquals($expected, $this->config->toArray());
    }
} 
