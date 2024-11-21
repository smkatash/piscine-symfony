<?php

namespace App\D07Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('d07');

        $treeBuilder->getRootNode()
            ->children()
                ->integerNode('number')
                    ->isRequired()
                    ->info('A mandatory integer value.')
                ->end()
                ->booleanNode('enable')
                    ->defaultTrue()
                    ->info('An optional boolean value. Defaults to true.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}


?>