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
}