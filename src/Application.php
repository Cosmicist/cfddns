<?php namespace Flatline\CfDdns;

use Flatline\CfDdns\Config\ConfigInterface;
use Flatline\CfDdns\CloudFlare\Api\RequestInterface;
use Symfony\Component\Console\Application as SfApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Config\FileLocatorInterface;

class Application extends SfApplication
{
    protected $config;

    protected $locator;

    public function __construct(ConfigInterface $config, RequestInterface $cfrequest)
    {
        $this->config = $config;

        $cfrequest->setToken($this->config['cf']['api_key']);
        $cfrequest->setEmail($this->config['cf']['email']);

        $this->cfrequest = $cfrequest;

        parent::__construct($name = 'CloudFlare DDNS', $version = 'UNKNOWN');
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new Command\Update($this->config, $this->cfrequest);

        return $commands;
    }
}
