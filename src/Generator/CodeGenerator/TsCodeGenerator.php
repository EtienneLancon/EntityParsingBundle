<?php

namespace EntityParsingBundle\Generator\CodeGenerator;

use EntityParsingBundle\Generator\AbstractCodeGenerator;

class TsCodeGenerator extends AbstractCodeGenerator
{
    public function __construct()
    {
        self::$language = 'TypeScript';
    }
}