<?php

namespace EntityParsingBundle\Generator;

use Doctrine\ORM\Mapping\MappingAttribute;

interface CodeGeneratorInterface
{
    public function doCodeForSinglePropertyAnnotation(string $propertyName, MappingAttribute $annotation): string;
}