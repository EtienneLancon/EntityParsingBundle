<?php

namespace EntityParsingBundle\Generator\EntityParser;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
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

    public function checkInverseJoin(string $targetEntity, string $targetProperty, string $sourceProperty): void
    {
        $reflectionClass = $this->em->getClassMetadata($targetEntity)->getReflectionClass();

        if(!$reflectionClass->hasProperty($targetProperty))
            throw new NoPropertyFoundException('Property '.$targetProperty.' does not exist in '.$targetEntity.', referenced in '.$this->entity.' field '.$sourceProperty.'.');

        $reflectionProperty = $reflectionClass->getProperty($targetProperty);

        $annotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);

        foreach($annotations as $annotation) {
            if(isset($annotation->mappedBy))
            {
                if($annotation->targetEntity !== $this->entity)
                    throw new NoEntityFoundException('Entity '.$this->entity.' references property '.$targetProperty.' in '.$targetEntity.' with mappedBy annotation, but the targetEntity does not match.');
                
                if($annotation->mappedBy !== $sourceProperty)
                    throw new NoPropertyFoundException('Entity '.$this->entity.' references property '.$sourceProperty.' in '.$targetEntity.' with mappedBy annotation, but it was not found. Found '.$annotation->mappedBy.' instead.');
                
                return;
            }

            if(isset($annotation->inversedBy))
            {
                if($annotation->targetEntity !== $this->entity)
                    throw new NoEntityFoundException('Entity '.$this->entity.' references property '.$targetProperty.' in '.$targetEntity.' with inversedBy annotation, but the targetEntity does not match.');
                
                if($annotation->inversedBy !== $sourceProperty)
                    throw new NoPropertyFoundException('Entity '.$this->entity.' references property '.$sourceProperty.' in '.$targetEntity.' with inversedBy annotation, but it was not found. Found '.$annotation->inversedBy.' instead.');
                
                return;
            }
        }
    
        throw new NoPropertyFoundException('Property '.$targetProperty.' does not have a mappedBy annotation, referenced in '.$targetEntity.'.');
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

    public function getEntity(): string
    {
        return $this->entity;
    }
}