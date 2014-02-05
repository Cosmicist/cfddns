<?php namespace Flatline\CfDdns\Command;

use Flatline\CfDdns\Config\ConfigInterface;
use Flatline\CfDdns\CloudFlare\Api\RequestInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Update extends Command
{
    protected $name = 'update';
    protected $description = 'Update cloudflare dns A record';

    protected $config;
    protected $cfrequest;

    public function __construct(ConfigInterface $config, RequestInterface $cfrequest)
    {
        $this->config = $config;
        $this->cfrequest = $cfrequest;

        parent::__construct($this->name);
    }

    protected function fire()
    {
        $this->info("Updating CloudFlare...");

        $subdomain = $this->config['cf.subdomain'];
        $domain = $this->config['cf.domain'];
        $service_mode = (int) $this->config['cf.service_mode'];
        $ttl = $this->config->get('cf.ttl', 1);

        // Get current IP address
        $ip = trim(file_get_contents($this->config->get('ip_service', 'http://icanhazip.com')));

        // Update record
        $rs = $this->cfrequest->edit($subdomain, $domain, $ip, $service_mode, $ttl);

        if ($rs->result != 'success')
        {
            $this->error($rs->msg);
        }
        else
        {
            $this->line("Updated host <comment>$subdomain.$domain</comment> A record to <comment>$ip</comment>");
        }
    }
}