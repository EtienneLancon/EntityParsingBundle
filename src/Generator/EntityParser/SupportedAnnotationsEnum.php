<?php

namespace EntityParsingBundle\Generator\EntityParser;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\TableGenerator;
use Doctrine\ORM\Mapping\JoinColumns;
use Doctrine\ORM\Mapping\MappingAttribute;

class SupportedAnnotationsEnum
{
    const ID = Id::class;
    const COLUMN = Column::class;
    const MANY_TO_ONE = ManyToOne::class;
    const ONE_TO_MANY = OneToMany::class;
    const MANY_TO_MANY = ManyToMany::class;
    const JOIN_COLUMN = JoinColumn::class;
    const JOIN_TABLE = JoinTable::class;
    const INHERITANCE_TYPE = InheritanceType::class;
    const DISCRIMINATOR_COLUMN = DiscriminatorColumn::class;
    const ENTITY = Entity::class;
    const TABLE = Table::class;
    const UNIQUE_CONSTRAINT = UniqueConstraint::class;
    const INDEX = Index::class;
    const GENERATED_VALUE = GeneratedValue::class;
    const SEQUENCE_GENERATOR = SequenceGenerator::class;
    const TABLE_GENERATOR = TableGenerator::class;
    const JOIN_COLUMNS = JoinColumns::class;

    const VALUES = [
        'ID' => Id::class,
        'COLUMN' => Column::class,
        'MANY_TO_ONE' => ManyToOne::class,
        'ONE_TO_MANY' => OneToMany::class,
        'MANY_TO_MANY' => ManyToMany::class,
        'JOIN_COLUMN' => JoinColumn::class,
        'JOIN_TABLE' => JoinTable::class,
        'INHERITANCE_TYPE' => InheritanceType::class,
        'DISCRIMINATOR_COLUMN' => DiscriminatorColumn::class,
        'ENTITY' => Entity::class,
        'TABLE' => Table::class,
        'UNIQUE_CONSTRAINT' => UniqueConstraint::class,
        'INDEX' => Index::class,
        'GENERATED_VALUE' => GeneratedValue::class,
        'SEQUENCE_GENERATOR' => SequenceGenerator::class,
        'TABLE_GENERATOR' => TableGenerator::class,
        'JOIN_COLUMNS' => JoinColumns::class
    ];

    static public function getType(MappingAttribute $annotation): ?string
    {
        return array_search($class = get_class($annotation), self::VALUES) !== false ? $class : null;
    }
}