<?php

namespace EntityParsingBundle\Generator;

use Doctrine\ORM\Mapping\MappingAttribute;
use Symfony\Component\Console\Style\SymfonyStyle;

use EntityParsingBundle\Configuration\ConfigurationDefinition;
use EntityParsingBundle\Enum\SupportedAnnotationsEnum;
use EntityParsingBundle\Generator\EntityParser\EntityParser;
use EntityParsingBundle\Generator\Structure\PropertyTypeInterface;
use EntityParsingBundle\Generator\Structure\PropertyPrototypeFactory;


abstract class AbstractCodeGenerator implements CodeGeneratorInterface
{
    protected SymfonyStyle $io;
    protected ConfigurationDefinition $config;
    protected array $currentPropertyAnnotations = [];
    protected ?PropertyTypeInterface $currentPropertyPrototype = null;
    protected ?string $currentPropertyName = null;

    public function __construct(ConfigurationDefinition $config, SymfonyStyle $io)
    {
        $this->io = $io;
        $this->config = $config;
    }

    public function readSinglePropertyAnnotation(string $propertyName, MappingAttribute $annotation, $entityParser): void
    {
        if(isset($this->currentPropertyName) && $this->currentPropertyName !== $propertyName)
        {
            $this->doCurrentPropertyPrototype($entityParser);
            $this->doCode();
            $this->currentPropertyAnnotations = [];
        }

        $this->currentPropertyName = $propertyName;

        if(SupportedAnnotationsEnum::isValid($annotation))
        {
            $this->currentPropertyAnnotations[] = $annotation;
        }
    }

    public function doCurrentPropertyPrototype(EntityParser $entityParser): void
    {
        $this->currentPropertyPrototype = PropertyPrototypeFactory::create($this->currentPropertyName, $this->currentPropertyAnnotations);

        foreach($this->currentPropertyAnnotations as $annotation) {
            $this->currentPropertyPrototype->handleAnnotation($annotation, $entityParser);
        }
    }

    public function reset(): void
    {
        $this->currentPropertyAnnotations = [];
        $this->currentPropertyPrototype = null;
        $this->currentPropertyName = null;
    }
}