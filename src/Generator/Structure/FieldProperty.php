<?php

namespace EntityParsingBundle\Generator\Structure;

use EntityParsingBundle\Enum\PropertyTypeEnum;

use Doctrine\ORM\Mapping\MappingAttribute;
use EntityParsingBundle\Generator\EntityParser\EntityParser;

class FieldProperty extends AbstractPropertyType
{
    protected string $propertyType = PropertyTypeEnum::TYPE_FIELD;
    private bool $nullable;
    private string $dataType;
    private int $length;

    public function getNullable(): bool
    {
        return $this->nullable;
    }

    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function setDataType(string $dataType): void
    {
        $this->dataType = $dataType;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function handleAnnotation(MappingAttribute $annotation, EntityParser $entityParser): void
    {
        
    }
}