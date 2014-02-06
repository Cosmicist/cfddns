<?php namespace Flatline\CfDdns\Command;

use Flatline\CfDdns\Config\ConfigInterface;
use Flatline\CfDdns\CloudFlare\Api\RequestInterface;
use Symfony\Component\Console\Command\Command as SfCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SfCommand
{
    /** @var InputInterface */
    protected $input;

    /** @var OutputInterface */
    protected $output;

    /** @var ConfigInterface */
    protected $config;

    /** @var RequestInterface */
    protected $cfrequest;

    /**
     * Set to true if the command needs to initialize the request
     *
     * This also involves loading the config beforehand
     *
     * @var boolean
     */
    protected $init_request = false;

    public function __construct(ConfigInterface $config, RequestInterface $cfrequest)
    {
        $this->config = $config;
        $this->cfrequest = $cfrequest;

        parent::__construct($this->name);
    }

    protected function configure()
    {
        $this
            ->setName($this->name)
            ->setDescription($this->description)
        ;

        // Arguments
        $arguments = $this->getArguments();

        foreach ($arguments as $argument)
        {
            list($name, $mode, $description, $default) = $argument;

            $this->addArgument($name, $mode, $description, $default);
        }

        // Options
        $options = $this->getOptions();

        foreach ($options as $option)
        {
            list($name, $shortcut, $mode, $description, $default) = $option;

            $this->addOption($name, $mode, $description, $default);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set input/output directly to the class
        $this->input = $input;
        $this->output = $output;

        if ($this->init_request)
        {
            // Try to load the config
            try {
                $this->config->load();
            } catch (\InvalidArgumentException $ex) {
                $this->error($ex->getMessage()); exit;
            }

            // Initialize the CloudFlare request
            $this->cfrequest->setToken($this->config['cf']['api_key']);
            $this->cfrequest->setEmail($this->config['cf']['email']);
        }


        $this->fire();
    }

    abstract protected function fire();

    protected function getArguments()
    {
        return [];
    }

    protected function getOptions()
    {
        return [];
    }

    protected function getArgument($option)
    {
        return $this->input->getArgument($option);
    }

    protected function getOption($option)
    {
        return $this->input->getOption($option);
    }

    protected function line($str)
    {
        $this->output->writeln($str);
    }

    protected function info($str)
    {
        $this->line("<info>$str</info>");
    }

    protected function comment($str)
    {
        $this->line("<comment>$str</comment>");
    }

    protected function error($str)
    {
        $this->line("<error>$str</error>");
    }
}
