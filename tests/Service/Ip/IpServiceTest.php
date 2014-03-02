<?php

namespace Flatline\CfDdns\Service\Ip;

use Mockery as m;

class IpServiceTest extends \TestCase
{
    /**
     * @var IpService
     */
    protected $ipservice;

    /**
     * @var m\Mock
     */
    protected $provider;

    public function setUp()
    {
        $this->provider = m::mock('Flatline\CfDdns\Service\Ip\Providers\IpProviderInterface');

        $this->ipservice = new IpService($this->provider);
    }

    public function testGetIpAddress()
    {
        $this->provider->shouldReceive('getIp')->once()->andReturn('foo');

        $this->assertEquals('foo', $this->ipservice->get());
    }
} 
