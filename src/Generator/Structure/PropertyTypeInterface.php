<?php

namespace EntityParsingBundle\Generator\Structure;

use Doctrine\ORM\Mapping\MappingAttribute;

use EntityParsingBundle\Generator\EntityParser\EntityParser;

interface PropertyTypeInterface
{
    public function getPropertyType(): string;
    public function handleAnnotation(MappingAttribute $annotation, EntityParser $entityParser): void;
}