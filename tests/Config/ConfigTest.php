<?php namespace Flatline\CfDdns\Config;

use Mockery as m;
use Symfony\Component\Yaml\Dumper;

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
     * @var m\Mock
     */
    protected $dumper;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var String
     */
    protected $configFilename;

    /**
     * @var array
     */
    protected $configArray = ['foo' => ['bar' => 'baz']];


    public function setUp()
    {
        $this->configFilename = "test.yml";

        $this->locator = m::mock('Symfony\Component\Config\FileLocatorInterface');

        $this->resolver = m::mock('Symfony\Component\Config\Loader\LoaderResolverInterface');

        $this->dumper = m::mock('Symfony\Component\Yaml\Dumper');

        $this->config = new Config($this->configFilename, $this->locator, $this->resolver, $this->dumper);

        $loader = m::mock('Flatline\CfDdns\Config\Loader\YmlLoader');
        $loader->shouldReceive('load')->andReturn($this->configArray);

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

    public function tearDown()
    {
        if (file_exists($f = $this->fixture('test.yml'))) {
            unlink($f);
        }
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

    public function testSetOrReplace()
    {
        $this->config->items(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $this->config->toArray());
    }

    public function testSaveConfigFile()
    {
        $this->dumper->shouldReceive('dump')->withArgs([$this->configArray, 5])->once()->andReturn('foo: bar');

        $configFilename = $this->fixture('test.yml');

        $this->assertTrue($this->config->save($this->fixture('')));

        $this->assertFileExists($configFilename);

        $this->assertStringEqualsFile($configFilename, 'foo: bar');
    }
} 
