<?php

namespace EntityParsingBundle\Configuration;

use EntityParsingBundle\Generator\SupportedLanguagesEnum;

use EntityParsingBundle\Exception\NoEntityFoundException;
use EntityParsingBundle\Exception\PathNotFoundException;
use EntityParsingBundle\Exception\PathNotWritableException;
use EntityParsingBundle\Exception\PathNotReadableException;
use EntityParsingBundle\Exception\UnsuportedLanguageException;

class ConfigurationDefinition
{
    private string $sourcePath;
    private string $targetPath;
    private string $language;

    public function __construct(array $configuration)
    {
        $this->sourcePath = $configuration['source_path'];
        $this->targetPath = $configuration['target_path'];
        $this->language = $configuration['language'];

        $this->doCheck();
    }

    public function draw(): string
    {
        return 'Parsing '.$this->sourcePath.' into '.$this->targetPath.' in '.$this->language;
    }

    private function doCheck(): void
    {
        $this->checkPaths();
        $this->checkLanguage();
    }

    private function checkPaths(): void
    {
        if (!is_dir($this->sourcePath)) {
            throw new PathNotFoundException($this->sourcePath.' is not a directory');
        }

        if (!is_readable($this->sourcePath)) {
            throw new PathNotReadableException($this->sourcePath.' is not readable');
        }

        if(empty(preg_grep('~.php~', scandir($this->sourcePath)))) {
            throw new NoEntityFoundException('No entity found in '.$this->sourcePath);
        }

        if (!is_dir($this->targetPath)) {
            throw new PathNotFoundException($this->targetPath.' is not a directory');
        }

        if (!is_writable($this->targetPath)) {
            throw new PathNotWritableException($this->targetPath.' is not writable');
        }
    }

    private function checkLanguage(): void
    {
        if (!in_array($this->language, SupportedLanguagesEnum::getValues())) {
            throw new UnsuportedLanguageException('Language '.$this->language.' is not supported');
        }
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getSourcePath(): string
    {
        return $this->sourcePath;
    }

    public function getTargetPath(): string
    {
        return $this->targetPath;
    }
}