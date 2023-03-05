<?php

namespace EntityParsingBundle\Generator;

use EntityParsingBundle\Exception\UnsuportedLanguageException;
use EntityParsingBundle\Generator\SupportedLanguagesEnum;

use EntityParsingBundle\Generator\CodeGenerator\JsCodeGenerator;
use EntityParsingBundle\Generator\CodeGenerator\TsCodeGenerator;
use EntityParsingBundle\Generator\CodeGenerator\PythonCodeGenerator;

use EntityParsingBundle\Configuration\ConfigurationDefinition;
use Symfony\Component\Console\Style\SymfonyStyle;

class CodeGeneratorFactory
{
    public static function create(ConfigurationDefinition $config, SymfonyStyle $io): CodeGeneratorInterface
    {
        switch ($config->getLanguage()) {
            case SupportedLanguagesEnum::JAVASCRIPT:
                return new JsCodeGenerator($config, $io);
            case SupportedLanguagesEnum::TYPESCRIPT:
                return new TsCodeGenerator($config, $io);
            case SupportedLanguagesEnum::PYTHON:
                return new PythonCodeGenerator($config, $io);
            default:
                throw new UnsuportedLanguageException('Language '.$config->getLanguage().' is not supported.');
        }
    }
}