<?php

namespace EntityParsingBundle\Generator\Structure;

use EntityParsingBundle\Enum\SupportedAnnotationsEnum;

use EntityParsingBundle\Exception\UnableToCreateFieldDescriptionException;

class PropertyPrototypeFactory
{
    const CASES_FIELD = [SupportedAnnotationsEnum::COLUMN
                        , SupportedAnnotationsEnum::ID
                        , SupportedAnnotationsEnum::GENERATED_VALUE
                    ];
    const CASES_JOIN = [SupportedAnnotationsEnum::JOIN_COLUMN
                        , SupportedAnnotationsEnum::JOIN_TABLE
                        , SupportedAnnotationsEnum::JOIN_COLUMNS
                        , SupportedAnnotationsEnum::MANY_TO_ONE
                        , SupportedAnnotationsEnum::ONE_TO_MANY
                        , SupportedAnnotationsEnum::MANY_TO_MANY
                        , SupportedAnnotationsEnum::JOIN_COLUMNS];

    static public function create(string $fieldName, array $annotations): PropertyTypeInterface
    {
        foreach ($annotations as $annotation) {
            $class = get_class($annotation);

            if(in_array($class, self::CASES_FIELD)) {
                return self::createFieldDescription($fieldName);
            }

            if(in_array($class, self::CASES_JOIN)) {
                return self::createJoinDescription($fieldName);
            }
        }
        throw new UnableToCreateFieldDescriptionException("Unable to create property prototype for field: $fieldName");
    }

    static private function createFieldDescription(string $fieldName): PropertyTypeInterface
    {
        return new FieldProperty($fieldName);
    }

    static private function createJoinDescription(string $fieldName): PropertyTypeInterface
    {
        return new JoinProperty($fieldName);
    }
}