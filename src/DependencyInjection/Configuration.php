<?php

namespace EntityParsingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use EntityParsingBundle\Enum\SupportedLanguagesEnum;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('entity_parsing_bundle');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('managers')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('language')
                                ->cannotBeEmpty()
                                ->values(SupportedLanguagesEnum::getValues())
                            ->end()
                            ->scalarNode('target_path')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('manager_name')
                                ->defaultValue('default')
                                ->cannotBeEmpty()
                            ->end()
                            ->booleanNode('private_properties')
                                ->defaultTrue()
                            ->end()
                            ->booleanNode('generate_getters')
                                ->defaultTrue()
                            ->end()
                            ->booleanNode('generate_setters')
                                ->defaultTrue()
                            ->end()
                            ->booleanNode('generate_constructor')
                                ->defaultTrue()
                            ->end()
                            ->booleanNode('interface')
                                ->defaultFalse()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

