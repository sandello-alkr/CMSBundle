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
        $rootNode = $treeBuilder->root('cms','array');

        $rootNode
            ->children()
                ->scalarNode('children_type')
                    ->defaultValue('list')
                    ->validate()
                        ->ifNotInArray(array('list', 'preview', 'none'))
                        ->thenInvalid('Invalid children_type "%s"')
                    ->end()
                ->end()
                ->scalarNode('base_template')
                    ->defaultValue('two_sidebars.html.twig')
                    ->validate()
                        ->ifNotInArray(array('two_sidebars.html.twig', 'one_sidebar.html.twig', 'full_width.html.twig'))
                        ->thenInvalid('Invalid base template "%s"')
                    ->end()
                ->end()
                ->booleanNode('url_by_path')->defaultFalse()->end()
                ->integerNode('top_menu_parent')->defaultValue(5)->end()
                ->integerNode('left_menu_parent')->defaultValue(1)->end()
                ->arrayNode('left_sidebar')
                    ->children()
                        ->booleanNode('menu')->defaultValue(true)->end()
                        ->booleanNode('right')->defaultValue(false)->end()
                        ->booleanNode('search')->defaultValue(false)->end()
                        ->booleanNode('posts')->defaultValue(false)->end()
                        ->booleanNode('banner')->defaultValue(false)->end()
                        ->booleanNode('gallery')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('right_sidebar')
                    ->children()
                        ->booleanNode('menu')->defaultValue(false)->end()
                        ->booleanNode('right')->defaultValue(false)->end()
                        ->booleanNode('search')->defaultValue(false)->end()
                        ->booleanNode('posts')->defaultValue(false)->end()
                        ->booleanNode('banner')->defaultValue(false)->end()
                        ->booleanNode('gallery')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('header')
                    ->children()
                        ->scalarNode('menu')
                            ->defaultValue('over')
                            ->validate()
                                ->ifNotInArray(array('over', 'under', 'on'))
                                ->thenInvalid('Menu can be "over", "under" or "on", got "%s"')
                            ->end()
                        ->end()
                        ->booleanNode('slider')->defaultValue(false)->end()
                        ->booleanNode('search')->defaultValue(false)->end()
                        ->arrayNode('navbar')
                            ->children()
                                ->booleanNode('main')->defaultValue(true)->end()
                                ->booleanNode('reviews')->defaultValue(false)->end()
                                ->booleanNode('inverse')->defaultValue(false)->end()
                                ->booleanNode('narrow')->defaultValue(false)->end()
                                ->booleanNode('fixed')->defaultValue(false)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('footer')
                    ->children()
                        ->booleanNode('menu')->defaultValue(false)->end()
                        ->booleanNode('faq')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('modules')
                    ->children()
                        ->booleanNode('feedback')->defaultValue(false)->end()
                        ->booleanNode('map')->defaultValue(false)->end()
                        ->booleanNode('gallery')->defaultValue(false)->end()
                        ->booleanNode('redirects')->defaultValue(false)->end()
                        ->booleanNode('posts')->defaultValue(false)->end()
                        ->booleanNode('views')->defaultValue(false)->end()
                        ->booleanNode('dropdown_menu')->defaultValue(false)->end()
                        ->booleanNode('comments')->defaultValue(false)->end()
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
