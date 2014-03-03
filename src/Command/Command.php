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

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;

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

        $this->fire();
    }

    abstract protected function fire();

    public function getArguments()
    {
        return [];
    }

    public function getOptions()
    {
        return [];
    }

    public function getArgument($option)
    {
        return $this->input->getArgument($option);
    }

    public function getOption($option)
    {
        return $this->input->getOption($option);
    }

    public function line($str)
    {
        $this->output->writeln($str);
    }

    public function info($str)
    {
        $this->line("<info>$str</info>");
    }

    public function comment($str)
    {
        $this->line("<comment>$str</comment>");
    }

    public function error($str)
    {
        $this->line("<error>$str</error>");
    }

    public function ask($question, $default = null, array $autocomplete = null)
    {
        /** @var $dialog \Symfony\Component\Console\Helper\DialogHelper */
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->ask($this->output, "<question>$question</question> ", $default, $autocomplete);
    }
}
