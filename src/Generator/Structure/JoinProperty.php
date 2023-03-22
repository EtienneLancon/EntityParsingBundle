<?php

namespace EntityParsingBundle\Generator\Structure;

use Doctrine\ORM\Mapping\MappingAttribute;

use EntityParsingBundle\Enum\PropertyTypeEnum;
use EntityParsingBundle\Enum\SupportedAnnotationsEnum;
use EntityParsingBundle\Generator\EntityParser\EntityParser;
use EntityParsingBundle\Exception\WrongAnnotationConfigurationException;

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
            case SupportedAnnotationsEnum::MANY_TO_ONE || SupportedAnnotationsEnum::MANY_TO_MANY || SupportedAnnotationsEnum::ONE_TO_ONE:
                $this->checkDoubleJoinDeclaration($entityParser);
                $this->joinType = $type;
                $this->joinEntity = $annotation->targetEntity;

                if(isset($annotation->inversedBy))
                {
                    $entityParser->checkInverseJoin($this->joinEntity, $annotation->inversedBy, $this->propertyName);
                }
                
                break;

            case SupportedAnnotationsEnum::ONE_TO_MANY:
                $this->checkDoubleJoinDeclaration($entityParser);
                $this->joinType = $type;
                $this->joinEntity = $annotation->targetEntity;

                if(isset($annotation->mappedBy))
                {
                    $entityParser->checkInverseJoin($this->joinEntity, $annotation->mappedBy, $this->propertyName);
                }
                
                break;

            case SupportedAnnotationsEnum::JOIN_COLUMNS || SupportedAnnotationsEnum::JOIN_COLUMN:
                // $this->addJoinColumn($annotation);
                // dump($this->joinColumns);
                break;
        }
    }

    private function checkDoubleJoinDeclaration(EntityParser $entityParser): void
    {
        if(isset($this->joinType) || isset($this->joinEntity))
            throw new WrongAnnotationConfigurationException("Join type or entity already set for property ".$this->propertyName." in entity ".$entityParser->getEntity().".");
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

    public function addJoinColumn(MappingAttribute $annotation): void
    {
        $this->joinColumns[] = new JoinColumn($annotation);
    }
}