<?php

namespace EntityParsingBundle\Generator;

class SupportedLanguagesEnum
{
    public const PYTHON = 'python';
    public const JAVASCRIPT = 'js';
    public const TYPESCRIPT = 'ts';

    static public function getValues(): array
    {
        return [
            self::PYTHON,
            self::JAVASCRIPT,
            self::TYPESCRIPT,
        ];
    }

    static public function isValid(string $language): bool
    {
        return in_array($language, self::getValues());
    }

    static public function getLabel(string $language): string
    {
        switch($language) {
            case self::PYTHON:
                return 'Python';
            case self::JAVASCRIPT:
                return 'JavaScript';
            case self::TYPESCRIPT:
                return 'TypeScript';
        }
    }
}