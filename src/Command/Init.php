<?php namespace Flatline\CfDdns\Command;

use Flatline\CfDdns\Config\ConfigInterface;

class Init extends Command
{
    protected $name = 'init';
    protected $description = 'Init the config file and place it on your home';

    /**
     * @var string
     */
    protected $homeDir;

    public function __construct(ConfigInterface $config, $homeDir)
    {
        parent::__construct($config);

        $this->homeDir = $homeDir;
    }

    protected function fire()
    {
        $this->info("CloudFlare DDNS Updater Config Generator");
        $this->line('');

        $config = [];
        $config['cf']['api_key'] = $this->askApiKey();
        $config['cf']['email'] = $this->askEmail();
        $config['cf']['domain'] = $this->ask("Domain");
        $config['cf']['subdomain'] = $this->ask("Subdomain");
        $config['cf']['ttl'] = $this->ask("Record TTL [default: 1 (automatic)]", 1);
        $config['cf']['service_mode'] = (int) $this->ask("Service mode [1 = orange cloud (default) | 0 = grey cloud]", 1, [0,1]);
        $config['ip_service'] = $this->askIpService();

        $this->line('');

        $this->line("Saved to <info>{$this->homeDir}/{$this->config->getFilename()}</info>");

        $this->config->items($config)->save($this->homeDir);
    }

    protected function askApiKey()
    {
        $apiKey = $this->ask("Your CloudFlare API key");

        if (!preg_match('/^[a-f0-9]+$/i', $apiKey)) {
            return $this->askApiKey();
        }

        return $apiKey;
    }

    protected function askEmail()
    {
        $email = $this->ask("Your CloudFlare email");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->askEmail();
        }
    }

    protected function askIpService()
    {
        $ipService = $this->ask(
            "IP Service [default: http://icanhazip.com]",
            'http://icanhazip.com',
            ['icanhazip.com', 'http://icanhazip.com']
        );

        if (!$ipService) {
            return $this->askIpService();
        }

        if (!preg_match('/^https?:\/\//i', $ipService)) {
            $ipService = "http://$ipService";
        }

        return $ipService;
    }
}
