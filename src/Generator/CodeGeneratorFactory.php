<?php

namespace EntityParsingBundle\Generator;

use EntityParsingBundle\Exception\UnsuportedLanguageException;
use EntityParsingBundle\Generator\SupportedLanguagesEnum;

use EntityParsingBundle\Generator\CodeGenerator\JsCodeGenerator;
use EntityParsingBundle\Generator\CodeGenerator\TsCodeGenerator;
use EntityParsingBundle\Generator\CodeGenerator\PythonCodeGenerator;
use Symfony\Component\Console\Style\SymfonyStyle;

class CodeGeneratorFactory
{
    public static function create(string $language, SymfonyStyle $io): CodeGeneratorInterface
    {
        $generator = null;

        switch ($language) {
            case SupportedLanguagesEnum::JAVASCRIPT:
                $generator = new JsCodeGenerator();
                break;
            case SupportedLanguagesEnum::TYPESCRIPT:
                $generator = new TsCodeGenerator();
                break;
            case SupportedLanguagesEnum::PYTHON:
                $generator = new PythonCodeGenerator();
                break;
            default:
                throw new UnsuportedLanguageException('Language '.$language.' is not supported.');
        }

        $generator->init($io);

        return $generator;
    }
}