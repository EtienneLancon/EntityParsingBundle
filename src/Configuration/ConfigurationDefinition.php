<?php

namespace EntityParsingBundle\Configuration;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

use EntityParsingBundle\Enum\SupportedLanguagesEnum;

use EntityParsingBundle\Exception\NoEntityFoundException;
use EntityParsingBundle\Exception\PathNotFoundException;
use EntityParsingBundle\Exception\PathNotWritableException;
use EntityParsingBundle\Exception\UnsupportedLanguageException;

class ConfigurationDefinition
{
    private string $targetPath;
    private string $language;
    private EntityManager $em;
    private string $namespace;
    private string $managerName;
    private bool $generateGetters;
    private bool $generateSetters;
    private bool $generateConstructor;
    private bool $privateProperties;
    private bool $interface;

    public function __construct(array $configuration, Registry $doctrine)
    {
        $this->targetPath = $configuration['target_path'];
        $this->language = $configuration['language'];
        $this->managerName = $configuration['manager_name'];
        $this->generateGetters = $configuration['generate_getters'];
        $this->generateSetters = $configuration['generate_setters'];
        $this->generateConstructor = $configuration['generate_constructor'];
        $this->privateProperties = $configuration['private_properties'];
        $this->interface = $configuration['interface'];

        $this->em = $doctrine->getManager($configuration['manager_name']);
        $this->namespace = array_values($this->em->getConfiguration()->getEntityNamespaces())[0];

        $this->doCheck();
    }

    public function draw(?string $entity = null): string
    {
        return 'Parsing '.(empty($entity) ? 'manager '.$this->managerName : $this->namespace.'\\'.$entity).' into '
            .$this->targetPath.' in '.SupportedLanguagesEnum::getLabel($this->language);
    }

    private function doCheck(): void
    {
        $this->checkPaths();
        $this->checkLanguage();
    }

    private function checkPaths(): void
    {
        if (!is_dir($this->targetPath)) {
            throw new PathNotFoundException($this->targetPath.' is not a directory');
        }

        if (!is_writable($this->targetPath)) {
            throw new PathNotWritableException($this->targetPath.' is found but is not writable');
        }
    }

    private function checkLanguage(): void
    {
        if (SupportedLanguagesEnum::isValid($this->language) === false) {
            throw new UnsupportedLanguageException('Language '.$this->language.' is not supported');
        }
    }

    public function isValidEntity(string $entity): bool
    {
        if(!class_exists($entity)){
            return false;
        }

        return true;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    public function getManager(): EntityManager
    {
        return $this->em;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getManagerName(): string
    {
        return $this->managerName;
    }

    public function getGenerateGetters(): bool
    {
        return $this->generateGetters;
    }

    public function getGenerateSetters(): bool
    {
        return $this->generateSetters;
    }

    public function getGenerateConstructor(): bool
    {
        return $this->generateConstructor;
    }

    public function getPrivateProperties(): bool
    {
        return $this->privateProperties;
    }

    public function getInterface(): bool
    {
        return $this->interface;
    }
}