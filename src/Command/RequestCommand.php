<?php namespace Flatline\CfDdns\Command;

use Flatline\CfDdns\Config\ConfigInterface;
use Flatline\CfDdns\CloudFlare\Api\RequestInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class RequestCommand extends Command
{
    /** @var RequestInterface */
    protected $cfrequest;

    public function __construct(ConfigInterface $config, RequestInterface $cfrequest)
    {
        $this->cfrequest = $cfrequest;

        parent::__construct($config);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set input/output directly to the class
        $this->input = $input;
        $this->output = $output;

        // Try to load the config
        try {
            $this->config->load();
        } catch (\InvalidArgumentException $ex) {
            $this->error($ex->getMessage()); exit;
        }

        // Initialize the CloudFlare request
        $this->cfrequest->setToken($this->config['cf']['api_key']);
        $this->cfrequest->setEmail($this->config['cf']['email']);

        // Fire the command
        $this->fire();
    }
}
