<?php

namespace EntityParsingBundle\Generator;

use Doctrine\ORM\Mapping\MappingAttribute;
use EntityParsingBundle\Generator\EntityParser\EntityParser;

interface CodeGeneratorInterface
{
    public function readSinglePropertyAnnotation(string $propertyName, MappingAttribute $annotation, EntityParser $entityParser): void;
    public function doCurrentPropertyPrototype(EntityParser $entityParser): void;
    public function doCode(): void;
    public function reset(): void;
}