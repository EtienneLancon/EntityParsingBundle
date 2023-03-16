<?php

namespace EntityParsingBundle\Generator\CodeGenerator;

use EntityParsingBundle\Generator\AbstractCodeGenerator;

class TsCodeGenerator extends AbstractCodeGenerator
{
    public function doCode(): void
    {
        dump($this->currentPropertyPrototype);
    }
}