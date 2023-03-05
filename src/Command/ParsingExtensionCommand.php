<?php

namespace EntityParsingBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;

abstract class ParsingExtensionCommand extends Command
{
    protected SymfonyStyle $io;
    protected Registry $doctrine;
    protected EntityManagerInterface $em;

    public function __construct(Registry $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output): int
    {
        if($input->getOption('help'))
        {
            exit(self::SUCCESS);
        }

        $input->validate();

        $this->io = new SymfonyStyle($input, $output);

        return self::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): int
    {
        $this->io->warning('This command will overwrite all existing files in the target path. Please make sure to backup your files before continuing.');

        if(!$this->io->confirm('Do you want to continue ?', false))
        {
            $this->io->warning('Aborted.');

            exit(self::SUCCESS);
        }

        return self::SUCCESS;
    }

    protected function title(array $list): void
    {
        $this->io->title('Entity Parsing Bundle');
        $this->io->text('This command will read all Doctrine entities and write them into configured language files.');
        $this->io->text('The following configurations will be used:');
        $this->io->newLine();

        $this->io->listing($list);
    }
}