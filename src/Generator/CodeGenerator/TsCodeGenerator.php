<?php

namespace EntityParsingBundle\Generator\CodeGenerator;

use EntityParsingBundle\Generator\AbstractCodeGenerator;

use Doctrine\ORM\Mapping\MappingAttribute;
use EntityParsingBundle\Generator\EntityParser\SupportedAnnotationsEnum;

class TsCodeGenerator extends AbstractCodeGenerator
{
    public function doCodeForSinglePropertyAnnotation(string $propertyName, MappingAttribute $annotation): string
    {
        if(($type = SupportedAnnotationsEnum::getType($annotation)) !== null)
        {
            switch($type)
            {
                case SupportedAnnotationsEnum::ID:
                    echo "this is an id brah ".$propertyName."\n";
                case SupportedAnnotationsEnum::COLUMN:
                    echo "this is a column brah ".$propertyName."\n";
                    break;
                    
            }
        }

        return '';
    }

    public function getCodeForTypeDeclaration(string $type, string $propertyName): string
    {
        switch($type)
        {
            case 'string':
                return $propertyName.': string';
            case 'integer':
                return $propertyName.': number';
            case 'boolean':
                return $propertyName.': boolean';
            case 'datetime':
                return $propertyName.': Date';
            default:
                return $type;
        }
    }
}