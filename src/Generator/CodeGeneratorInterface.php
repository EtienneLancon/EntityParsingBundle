<?php

namespace EntityParsingBundle\Generator;

interface CodeGeneratorInterface
{
    public function generate(string $entity);
    public function parseEntity(string $entity);
    public function parseEntities();
}