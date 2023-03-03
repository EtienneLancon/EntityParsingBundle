<?php

    namespace EntityParsingBundle\Command;

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class AllCommand extends Command
    {
        protected static $defaultName = 'entity_parsing_bundle:all';

        private array $entity_directories;

        public function __construct(array $entity_directories)
        {
            parent::__construct();
            $this->entity_directories = $entity_directories;
        }

        protected function configure()
        {
            $this
                ->setName('entity_parsing_bundle:all')
                ->setDescription('Make something')
                ->setHelp('Reads all source paths from config and writes them into related files in the corresponding target path. This command takes no arguments.');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $output->writeln([
                'Make something',
                '============',
                '',
            ]);

            foreach($this->entity_directories as $entity_directory) {
                $output->writeln('Entity directory: ' . implode(', ', $entity_directory));
            }

            $output->writeln('Made !');

            return Command::SUCCESS;
        }
    }