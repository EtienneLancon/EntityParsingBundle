<?php

namespace EntityParsingBundle\Generator\Structure;

class JoinColumn
{
    private string $joinColumn;
    private string $referencedColumnName;
    private string $fieldType;
    private bool $nullable = false;

    public function __construct(string $joinColumn, string $referencedColumnName, string $fieldType, bool $nullable = false)
    {
        $this->joinColumn = $joinColumn;
        $this->referencedColumnName = $referencedColumnName;
        $this->fieldType = $fieldType;
        $this->nullable = $nullable;
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