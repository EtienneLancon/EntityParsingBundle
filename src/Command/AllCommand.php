<?php

namespace EntityParsingBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EntityParsingBundle\Configuration\ConfigurationDefinition;
use EntityParsingBundle\Generator\EntityParserFactory;

use Doctrine\Bundle\DoctrineBundle\Registry;

class AllCommand extends ParsingExtensionCommand
{
    protected static $defaultName = 'entity_parsing_bundle:all';

    /**
     * @var ConfigurationDefinition[]
     */
    private array $configurations;

    public function __construct(Registry $doctrine, array $managers)
    {
        parent::__construct($doctrine);

        $instanciate = function($bundleManager) use ($doctrine) {
            return new ConfigurationDefinition($bundleManager, $doctrine);
        };

        $this->configurations = array_map($instanciate, $managers);
    }

    protected function configure(): void
    {
        $this
            ->setName('entity_parsing_bundle:all')
            ->setDescription('Reads Doctrine entities and writes them into configured language files.')
            ->setHelp('Reads all source paths from config and writes them into related files in the corresponding target path. This command takes no arguments.');
    }

    protected function interact(InputInterface $input, OutputInterface $output): int
    {
        $list = array_map(function($config) {
                    return $config->draw();
                }, $this->configurations);

        $this->title($list);

        return parent::interact($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $factory = new EntityParserFactory($this->io);

        foreach($this->configurations as $config)
        {
            $this->io->title($config->draw());
            
            $entityparser = $factory->create($config);
        }

        return self::SUCCESS;
    }
}