<?php

namespace EntityParsingBundle\Generator;

use EntityParsingBundle\Configuration\ConfigurationDefinition;

use Doctrine\ORM\Mapping\MappingAttribute;
use EntityParsingBundle\Generator\EntityParser\SupportedAnnotationsEnum;

use Symfony\Component\Console\Style\SymfonyStyle;


abstract class AbstractCodeGenerator implements CodeGeneratorInterface
{
    protected SymfonyStyle $io;
    protected ConfigurationDefinition $config;
    protected string $currentPropertyName = '';
    protected array $currentPropertyAnnotations = [];
    protected array $currentPropertyPrototype = [];

    public function __construct(ConfigurationDefinition $config, SymfonyStyle $io)
    {
        $this->io = $io;
        $this->config = $config;
    }

    public function readSinglePropertyAnnotation(string $propertyName, MappingAttribute $annotation): void
    {
        if($this->currentPropertyName && $this->currentPropertyName !== $propertyName)
        {
            $this->doCurrentPropertyPrototype();
            $this->doCode();
            $this->currentPropertyName = $propertyName;
            $this->currentPropertyAnnotations = [];
        }
        
        $this->currentPropertyName = $propertyName;

        if(SupportedAnnotationsEnum::isValid($annotation))
        {
            $this->currentPropertyAnnotations[] = $annotation;
        }
    }

    public function doCurrentPropertyPrototype(): void
    {
        echo "Property name: {$this->currentPropertyName}" . PHP_EOL;
        foreach($this->currentPropertyAnnotations as $annotation) {
            switch($type = SupportedAnnotationsEnum::getType($annotation))
            {
                case SupportedAnnotationsEnum::COLUMN:
                    $this->currentPropertyPrototype['type'] = $annotation->type;
                    break;
            }
        }
    }

    abstract public function doCode(): void;
}