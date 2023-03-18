<?php

namespace EntityParsingBundle\Enum;

class PropertyTypeEnum
{
    const TYPE_FIELD = 'field';
    const TYPE_JOIN = 'join';

    static public function getValues(): array
    {
        return [
            self::TYPE_FIELD,
            self::TYPE_JOIN,
        ];
    }

    static public function isValid(string $fieldType): bool
    {
        return in_array($fieldType, self::getValues());
    }
}