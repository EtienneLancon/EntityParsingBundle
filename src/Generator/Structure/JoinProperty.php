<?php

namespace EntityParsingBundle\Generator\Structure;

use Doctrine\ORM\Mapping\MappingAttribute;

use EntityParsingBundle\Enum\PropertyTypeEnum;
use EntityParsingBundle\Enum\SupportedAnnotationsEnum;
use EntityParsingBundle\Generator\EntityParser\EntityParser;

class JoinProperty extends AbstractPropertyType
{
    protected string $propertyType = PropertyTypeEnum::TYPE_JOIN;
    private string $joinType;
    private string $joinEntity;
    private array $joinColumns;

    public function handleAnnotation(MappingAttribute $annotation, EntityParser $entityParser): void
    {
        switch(($type = SupportedAnnotationsEnum::getType($annotation)))
        {
            case 'MANY_TO_ONE' || 'MANY_TO_MANY' || 'ONE_TO_ONE':
                if($annotation->inversedBy)
                {
                    $entityParser->checkForInverseJoin($this->joinEntity, $annotation->inversedBy);
                }

                $this->joinType = $type;
                $this->joinEntity = $annotation->targetEntity;
                
                break;
        }
    }

    public function getJoinType(): string
    {
        return $this->joinType;
    }

    public function getJoinEntity(): string
    {
        return $this->joinEntity;
    }

    public function getJoinColumns(): array
    {
        return $this->joinColumns;
    }

    public function getJoinColumn(string $name): JoinColumn
    {
        return $this->joinColumns[$name];
    }
}