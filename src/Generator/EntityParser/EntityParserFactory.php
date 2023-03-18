<?php

namespace EntityParsingBundle\Generator\EntityParser;

use Symfony\Component\Console\Style\SymfonyStyle;
use EntityParsingBundle\Configuration\ConfigurationDefinition;
use EntityParsingBundle\Enum\SupportedLanguagesEnum;
use EntityParsingBundle\Generator\EntityParser\EntityParser;

use EntityParsingBundle\Exception\UnsupportedLanguageException;

use EntityParsingBundle\Generator\CodeGeneratorInterface;
use EntityParsingBundle\Generator\CodeGenerator\JsCodeGenerator;
use EntityParsingBundle\Generator\CodeGenerator\TsCodeGenerator;
use EntityParsingBundle\Generator\CodeGenerator\PythonCodeGenerator;


class EntityParserFactory
{
    protected SymfonyStyle $io;
    protected ConfigurationDefinition $config;
    private CodeGeneratorInterface $codegen;
    private EntityParser $entityParser;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
        $this->entityParser = new EntityParser($io);
    }

    public function create(ConfigurationDefinition $config, ?string $entity = null): EntityParser
    {
        $this->config = $config;
        switch ($this->config->getLanguage()) {
            case SupportedLanguagesEnum::JAVASCRIPT:
                $this->codegen = new JsCodeGenerator($this->config, $this->io, $this->entityParser);
                break;
            case SupportedLanguagesEnum::TYPESCRIPT:
                $this->codegen = new TsCodeGenerator($this->config, $this->io, $this->entityParser);
                break;
            case SupportedLanguagesEnum::PYTHON:
                $this->codegen = new PythonCodeGenerator($this->config, $this->io, $this->entityParser);
                break;
            default:
                throw new UnsupportedLanguageException('Language '.$this->config->getLanguage().' is not supported.');
        }

        $this->entityParser->setup($this->config->getManager(), $this->codegen, $entity);

        return $this->entityParser;
    }
}