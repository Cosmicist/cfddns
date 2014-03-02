<?php namespace Flatline\CfDdns\Service\Ip\Providers;

class SimpleIpProvider implements IpProviderInterface
{
    /**
     * @var string
     */
    private $url;

    public function __construct($url)
    {

        $this->url = $url;
    }

    public function getIp()
    {
        return trim(file_get_contents($this->url));
    }
}
