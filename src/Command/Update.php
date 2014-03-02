<?php namespace Flatline\CfDdns\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Update extends RequestCommand
{
    protected $name = 'update';
    protected $description = 'Update cloudflare dns A record';

    protected function fire()
    {
        $this->info("Updating CloudFlare...");

        $subdomain = $this->config['cf.subdomain'];
        $domain = $this->config['cf.domain'];
        $service_mode = (int) $this->config['cf.service_mode'];
        $ttl = $this->config->get('cf.ttl', 1);

        // Get current IP address
        $ip = $this->ipService->get();

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
