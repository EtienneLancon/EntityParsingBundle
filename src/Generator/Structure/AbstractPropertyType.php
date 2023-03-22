<?php

namespace EntityParsingBundle\Generator\Structure;

use EntityParsingBundle\Enum\PropertyTypeEnum;

use EntityParsingBundle\Exception\InvalidPropertyTypeException;

abstract class AbstractPropertyType implements PropertyTypeInterface
{
    protected string $propertyType = 'setMePlease';
    protected string $propertyName;

    public function __construct(string $propertyName)
    {
        if (!PropertyTypeEnum::isValid($this->propertyType)) {
            throw new InvalidPropertyTypeException('Invalid property type: ' . $this->propertyType.' for property: '.$propertyName);
        }

        $this->propertyName = $propertyName;
    }

    public function getPropertyType(): string
    {
        return $this->propertyType;
    }
}