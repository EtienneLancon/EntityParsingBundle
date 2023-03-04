<?php

namespace EntityParsingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use EntityParsingBundle\Generator\SupportedLanguagesEnum;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('entity_parsing_bundle');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('entity_directories')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('source_path')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->enumNode('language')
                                ->cannotBeEmpty()
                                ->values(SupportedLanguagesEnum::getValues())
                            ->end()
                            ->scalarNode('target_path')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

