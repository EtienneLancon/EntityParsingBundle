<?php

namespace EntityParsingBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EntityParsingBundle\Configuration\ConfigurationDefinition;
use EntityParsingBundle\Generator\CodeGeneratorFactory;

class AllCommand extends ParsingExtensionCommand
{
    protected static $defaultName = 'entity_parsing_bundle:all';

    private array $configurations;

    public function __construct(array $entity_directories)
    {
        parent::__construct();

        $instanciate = function($entity_directory) {
            $config = new ConfigurationDefinition($entity_directory);
            return $config;
        };

        $this->configurations = array_map($instanciate, $entity_directories);
    }

    protected function configure()
    {
        $this
            ->setName('entity_parsing_bundle:all')
            ->setDescription('Make something')
            ->setHelp('Reads all source paths from config and writes them into related files in the corresponding target path. This command takes no arguments.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if($input->getOption('help'))
        {
            return ParsingExtensionCommand::SUCCESS;
        }
        
        foreach($this->configurations as $config)
        {
            $this->io->title($config->draw());

            $codegen = CodeGeneratorFactory::create($config->getLanguage(), $this->io);
        }

        return ParsingExtensionCommand::SUCCESS;
    }
}