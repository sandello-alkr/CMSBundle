<?php

namespace alkr\CMSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cms');

        $rootNode
            ->children()
                ->scalarNode('children_type')->defaultValue('list')->end()
                ->scalarNode('base_template')
                    ->defaultValue('two_sidebars.html.twig')
                    ->validate()
                        ->ifNotInArray(array('two_sidebars.html.twig', 'one_sidebar.html.twig', 'full_width.html.twig'))
                        ->thenInvalid('Invalid base template "%s"')
                    ->end()
                ->end()
                ->scalarNode('url_by_path')->defaultValue('false')->end()
                ->integerNode('top_menu_parent')->defaultValue(5)->end()
                ->integerNode('left_menu_parent')->defaultValue(1)->end()
                ->arrayNode('modules')
                    ->children()
                        ->booleanNode('feedback')->defaultValue('false')->end()
                        ->booleanNode('map')->defaultValue('false')->end()
                        ->booleanNode('gallery')->defaultValue('false')->end()
                        ->booleanNode('redirects')->defaultValue('false')->end()
                        ->booleanNode('posts')->defaultValue('false')->end()
                        ->booleanNode('views')->defaultValue('false')->end()
                    ->end()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
