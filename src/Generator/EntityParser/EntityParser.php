<?php

namespace EntityParsingBundle\Generator\EntityParser;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\JoinColumn;
use EntityParsingBundle\Exception\NoEntityFoundException;
use EntityParsingBundle\Exception\NoPropertyFoundException;
use Symfony\Component\Console\Style\SymfonyStyle;

use EntityParsingBundle\Generator\CodeGeneratorInterface;


class EntityParser
{
    private EntityManager $em;
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
            $this->parseEntity();
        } else {
            $this->parseEntities();
        }
    }

    public function checkInverseJoin(string $targetEntity, string $property, bool $mandatory = false): ?string
    {
        $reflectionClass = $this->em->getClassMetadata($targetEntity)->getReflectionClass();
        if(!isset($reflectionClass))
            throw new NoEntityFoundException('Entity '.$targetEntity.' does not exist, referenced in '.$this->entity.'.');

        $reflectionProperty = $reflectionClass->getProperty($property);
        if(!isset($reflectionProperty))
            throw new NoPropertyFoundException('Property '.$property.' does not exist, referenced in '.$targetEntity.'.');

        $annotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);

        foreach($annotations as $annotation) {
            if(isset($annotation->targetEntity))
            {
                if($annotation->targetEntity !== $this->entity)
                    throw new NoPropertyFoundException('Property '.$property.' does not have a mappedBy annotation with the value '.$this->entity.', referenced in '.$targetEntity.'.');
                else
                    return $annotation->mappedBy;
            }
        }
        
        if($mandatory)
            throw new NoPropertyFoundException('Property '.$property.' does not have a mappedBy annotation, referenced in '.$targetEntity.'.');

        return null;
    }

    private function parseEntities()
    {
        $metadataFactory = $this->em->getMetadataFactory();

        foreach ($metadataFactory->getAllMetadata() as $metadata) {
            $this->entity = $metadata->getName();
            $this->parseEntity();
        }
    }

    private function parseEntity()
    {
        if(!class_exists($this->entity)) {
            throw new NoEntityFoundException('Entity '.$this->entity.' does not exist.');
        }
        
        foreach($this->em->getClassMetadata($this->entity)->getReflectionClass()->getProperties() as $property) {
            $this->parseProperty($property);
        }

        $this->end();
    }

    private function parseProperty(\ReflectionProperty $property)
    {
        foreach($this->annotationReader->getPropertyAnnotations($property) as $annotation) {
            $this->codegen->readSinglePropertyAnnotation($property->name, $annotation, $this);
        }
    }

    private function end()
    {
        $this->codegen->doCurrentPropertyPrototype($this);
        $this->codegen->doCode();
        $this->codegen->reset();
    }
}