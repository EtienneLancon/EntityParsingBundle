<?php

namespace EntityParsingBundle\Generator;

use Symfony\Component\Console\Style\SymfonyStyle;
use EntityParsingBundle\Configuration\ConfigurationDefinition;
use EntityParsingBundle\Generator\SupportedLanguagesEnum;

use Doctrine\Common\Annotations\AnnotationReader;

abstract class AbstractCodeGenerator implements CodeGeneratorInterface
{
    protected SymfonyStyle $io;
    protected ConfigurationDefinition $config;
    protected string $languageLabel;

    public function __construct(ConfigurationDefinition $config, SymfonyStyle $io)
    {
        $this->io = $io;
        $this->config = $config;
        $this->languageLabel = SupportedLanguagesEnum::getLabel($this->config->getLanguage());
    }

    public function generate(string $entity = null)
    {
        if($entity) {
            $this->parseEntity($entity);
        } else {
            $this->parseEntities();
        }
    }

    public function parseEntities()
    {
        $em = $this->config->getManager();

        $metadataFactory = $em->getMetadataFactory();

        foreach ($metadataFactory->getAllMetadata() as $metadata) {
            $this->parseEntity($metadata->getName());
        }
    }

    public function parseEntity(string $entity)
    {
        $this->io->writeln('Parsing entity '.$entity);
    }
}