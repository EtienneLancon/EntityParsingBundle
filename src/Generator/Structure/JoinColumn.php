<?php

namespace EntityParsingBundle\Generator\Structure;

use Doctrine\ORM\Mapping\MappingAttribute;

class JoinColumn
{
    private string $joinColumn;
    private string $referencedColumnName;
    private string $fieldType;
    private bool $nullable = false;

    public function __construct(MappingAttribute $annotation)
    {
        $this->joinColumn = $annotation->name;
        $this->referencedColumnName = $annotation->referencedColumnName;
        $this->fieldType = $annotation->fieldType;
        $this->nullable = $annotation->nullable;
    }

    public function getJoinColumn(): string
    {
        return $this->joinColumn;
    }

    public function getReferencedColumnName(): string
    {
        return $this->referencedColumnName;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}