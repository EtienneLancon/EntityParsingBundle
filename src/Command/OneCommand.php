<?php

    namespace EntityParsingBundle\Command;

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\DependencyInjection\ContainerInterface;

    class OneCommand extends Command
    {
        protected static $defaultName = 'entity_parsing_bundle:one';

        private $container;

        public function __construct(ContainerInterface $container)
        {
            parent::__construct();
            $this->container = $container;
        }

        protected function configure()
        {
            $this
                ->setName('entity_parsing_bundle:one')
                ->setDescription('Make something')
                ->setHelp('Takes the name of a configured source entity directory as only argument and writes its content into the defined language and defined target path.')
                ->addArgument('name', InputArgument::REQUIRED, 'The index of the config you did in entity_parsing_bundle.yaml');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            if($input->getOption('help'))
            {
                return Command::SUCCESS;
            }

            $output->writeln([
                'Make something',
                '============',
                '',
            ]);

            $name = $input->getArgument('name');

            $output->writeln(implode(', ', $this->container->getParameter('entity_parsing_bundle.entity_directories')[$name]));

            // foreach($this->entity_directories as $entity_directory) {
            //     $output->writeln('Entity directory: ' . implode(', ', $entity_directory));
            // }

            $output->writeln('Made !');

            return Command::SUCCESS;
        }
    }