<?php namespace Flatline\CfDdns\Command;

use Flatline\CfDdns\Event\IpUpdateEvent;
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

        // Get current IP set in CloudFlare
        $cfIp = $this->cfrequest->getIp($subdomain, $domain);

        if ($cfIp !== $ip)
        {
            // Run user-defined commands
            $this->runUserCommands($cfIp, $ip);

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

    private function runUserCommands($oldIp, $newIp)
    {
        foreach ((array) $this->config['on_ip_update'] as $cmd)
        {
            $cmd = str_replace(['{old_ip}', '{new_ip}'], [$oldIp, $newIp], $cmd);

            exec($cmd);
        }
    }
}
