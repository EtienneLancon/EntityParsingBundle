<?php

namespace EntityParsingBundle\Generator;

use Doctrine\ORM\Mapping\MappingAttribute;

interface CodeGeneratorInterface
{
    public function readSinglePropertyAnnotation(string $propertyName, MappingAttribute $annotation): void;
    public function doCurrentPropertyPrototype(): void;
    public function doCode(): void;
}