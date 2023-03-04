<?php

namespace EntityParsingBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;

use EntityParsingBundle\Configuration\ConfigurationDefinition;
use EntityParsingBundle\Generator\CodeGeneratorFactory;

class OneCommand extends ParsingExtensionCommand
{
    protected static $defaultName = 'entity_parsing_bundle:one';

    private $container;
    private $config;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('entity_parsing_bundle:one')
            ->setDescription('Parses the given entity/directory and writes its content into the defined language and defined target path.')
            ->setHelp('Takes the name of a configured source entity directory as first argument and writes its content into the defined language and defined target path.
                If the second argument is given, only the given entity will be parsed. If not, all entities in the given directory will be parsed.')
            ->addArgument('config_name', InputArgument::REQUIRED, 'The index of the config you did in entity_parsing_bundle.yaml')
            ->addArgument('entity', InputArgument::OPTIONAL, 'The name of the entity you want to parse. If not given, all entities in the given directory will be parsed.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->getOption('help'))
        {
            return ParsingExtensionCommand::SUCCESS;
        }

        $config_name = $input->getArgument('config_name');


        $this->config = new ConfigurationDefinition($this->container->getParameter('entity_parsing_bundle.entity_directories')[$config_name]);

        $this->io->title($this->config->draw());

        dump($this->config);

        $codegen = CodeGeneratorFactory::create($this->config->getLanguage(), $this->io);

        return ParsingExtensionCommand::SUCCESS;
    }
}