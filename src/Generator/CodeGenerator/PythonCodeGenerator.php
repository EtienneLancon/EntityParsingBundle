<?php

namespace EntityParsingBundle\Generator\CodeGenerator;

use EntityParsingBundle\Generator\AbstractCodeGenerator;

class PythonCodeGenerator extends AbstractCodeGenerator
{
    public function __construct()
    {
        self::$language = 'Python';
    }
}