<?php

namespace EntityParsingBundle\Generator;

use EntityParsingBundle\Configuration\ConfigurationDefinition;

use Symfony\Component\Console\Style\SymfonyStyle;


abstract class AbstractCodeGenerator implements CodeGeneratorInterface
{
    protected SymfonyStyle $io;
    protected ConfigurationDefinition $config;

    public function __construct(ConfigurationDefinition $config, SymfonyStyle $io)
    {
        $this->io = $io;
        $this->config = $config;
    }
}