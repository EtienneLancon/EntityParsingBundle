<?php

namespace EntityParsingBundle\Generator;

use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractCodeGenerator implements CodeGeneratorInterface
{
    protected SymfonyStyle $io;
    protected static $language;

    public function init(SymfonyStyle $io)
    {
        $this->io = $io;
        $this->io->info('Generating '.self::$language.' code...');
    }
}