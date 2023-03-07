<?php

namespace EntityParsingBundle\Generator\EntityParser;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Style\SymfonyStyle;

use EntityParsingBundle\Generator\CodeGeneratorInterface;


class EntityParser
{
    private $em;
    private ?string $entity;
    private AnnotationReader $annotationReader;
    private CodeGeneratorInterface $codegen;
    private SymfonyStyle $io;
    

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
        $this->annotationReader = new AnnotationReader();
    }

    public function setup(EntityManager $em, CodeGeneratorInterface $codegen, ?string $entity = null)
    {
        $this->em = $em;
        $this->entity = $entity;
        $this->codegen = $codegen;
    }

    public function parse()
    {
        if($this->entity) {
            $this->parseEntity($this->entity);
        } else {
            $this->parseEntities();
        }
    }

    private function parseEntities()
    {
        $metadataFactory = $this->em->getMetadataFactory();

        foreach ($metadataFactory->getAllMetadata() as $metadata) {
            $this->parseEntity($metadata->getName());
        }
    }

    private function parseEntity(string $entity)
    {
        $this->io->writeln('Parsing entity '.$entity);
        foreach($this->em->getClassMetadata($entity)->getReflectionClass()->getProperties() as $property) {
            $this->parseProperty($property);
        }
    }

    private function parseProperty(\ReflectionProperty $property)
    {
        foreach($this->annotationReader->getPropertyAnnotations($property) as $annotation) {
            $this->codegen->doCodeForSinglePropertyAnnotation($property->name, $annotation);
        }
    }
}