<?php

namespace alkr\CMSBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CMSExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('cms', $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        $inputs = array('@CMSBundle/Resources/assets/templates/left_menu_'.$config['left_sidebar']['behavior'].'.scss');
        if($config['modules']['comments'])
            $inputs[] = '@FOSCommentBundle/Resources/assets/css/comments.css';
        if (true === isset($bundles['AsseticBundle'])) {
            foreach (array_keys($container->getExtensions()) as $name) {
                switch ($name) {
                    case 'assetic':
                        $container->prependExtensionConfig(
                            $name,
                            array(
                                'assets' => array(
                                    'slider' => array(
                                        'inputs'  => $inputs,
                                        'output'  => 'css/compiled/compiled.css'
                                        )
                                    )
                                )
                        );
                        break;
                }
            }
        }
    }
}
