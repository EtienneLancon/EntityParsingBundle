<?php

namespace EntityParsingBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;

use EntityParsingBundle\Configuration\ConfigurationDefinition;
use EntityParsingBundle\Generator\CodeGeneratorFactory;

use Doctrine\Bundle\DoctrineBundle\Registry;

class OneCommand extends ParsingExtensionCommand
{
    protected static $defaultName = 'entity_parsing_bundle:one';

    private ContainerInterface $container;
    private ConfigurationDefinition $config;
    private ?string $entity;

    public function __construct(Registry $doctrine, ContainerInterface $container)
    {
        parent::__construct($doctrine);
        $this->container = $container;
    }

    protected function configure(): void
    {
        $this
            ->setName('entity_parsing_bundle:one')
            ->setDescription('Parses the given entity/directory and writes its content into the defined language and defined target path.')
            ->setHelp('Takes the name of a configured source entity directory as first argument and writes its content into the defined language and defined target path.
                If the second argument is given, only the given entity will be parsed. If not, all entities in the given directory will be parsed.')
            ->addArgument('manager_name', InputArgument::REQUIRED, 'The name of the doctrine manager and of the config you did in entity_parsing_bundle.yaml')
            ->addArgument('entity', InputArgument::OPTIONAL, 'The name of the entity you want to parse. If not given, all entities in the given directory will be parsed.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): int
    {
        parent::initialize($input, $output);

        $manager_name = $input->getArgument('manager_name');
        $this->entity = ucfirst(strtolower($input->getArgument('entity')));

        if(!isset($this->container->getParameter('entity_parsing_bundle.managers')[$manager_name]))
        {
            $this->io->error('The given config name '.$manager_name.' not found in entity_parsing_bundle.yaml.');
            exit(self::INVALID);
        }

        $this->config = new ConfigurationDefinition($this->container->getParameter('entity_parsing_bundle.managers')[$manager_name], $this->doctrine);

        if(empty($this->entity))
        {
            return self::SUCCESS;
        }

        if(!$this->config->isValidEntity($this->entity))
        {
            $this->io->error('The given entity '.$this->entity.' does not exist.');
            exit(self::INVALID);
        }

        return self::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): int
    {
        $this->title([$this->config->draw($this->entity)]);

        return parent::interact($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $codegen = CodeGeneratorFactory::create($this->config, $this->io);

        $codegen->generate($this->entity);

        return self::SUCCESS;
    }
}