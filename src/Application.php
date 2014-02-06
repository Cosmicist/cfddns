<?php namespace Flatline\CfDdns;

use Flatline\CfDdns\Config\ConfigInterface;
use Flatline\CfDdns\CloudFlare\Api\RequestInterface;
use Symfony\Component\Console\Application as SfApplication;

class Application extends SfApplication
{
    protected $config;

    protected $locator;

    public function __construct(ConfigInterface $config, RequestInterface $cfrequest)
    {
        $this->config = $config;

        $this->cfrequest = $cfrequest;

        parent::__construct($name = 'CloudFlare DDNS', $version = 'UNKNOWN');
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new Command\Init($this->config);
        $commands[] = new Command\Update($this->config, $this->cfrequest);

        return $commands;
    }
}
