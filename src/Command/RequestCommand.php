<?php namespace Flatline\CfDdns\Command;

use Flatline\CfDdns\Config\ConfigInterface;
use Flatline\CfDdns\CloudFlare\Api\RequestInterface;
use Flatline\CfDdns\Service\Ip\IpService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class RequestCommand extends Command
{
    /**
     * @var RequestInterface
     */
    protected $cfrequest;
    /**
     * @var \Flatline\CfDdns\Service\Ip\IpService
     */
    protected $ipService;

    public function __construct(ConfigInterface $config, RequestInterface $cfrequest, IpService $ipService)
    {
        $this->cfrequest = $cfrequest;

        $this->ipService = $ipService;

        parent::__construct($config);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set input/output directly to the class
        $this->input = $input;
        $this->output = $output;

        // Try to load the config
        if (!$this->config->loaded()) {
            $this->error("Couldn't find a config file"); exit;
        }

        // Initialize the CloudFlare request
        $this->cfrequest->setToken($this->config['cf']['api_key']);
        $this->cfrequest->setEmail($this->config['cf']['email']);

        // Fire the command
        $this->fire();
    }
}
