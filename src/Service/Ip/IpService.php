<?php namespace Flatline\CfDdns\Service\Ip;

use Flatline\CfDdns\Service\Ip\Providers\IpProviderInterface;

class IpService
{
    /**
     * @var Providers\IpProviderInterface
     */
    private $ipProvider;

    public function __construct(IpProviderInterface $ipProvider)
    {
        $this->ipProvider = $ipProvider;
    }

    public function get()
    {
        return $this->ipProvider->getIp();
    }
}
