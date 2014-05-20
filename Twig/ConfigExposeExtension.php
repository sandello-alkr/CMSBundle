<?php

namespace alkr\CMSBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigExposeExtension extends \Twig_Extension
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getGlobals()
    {
        return array(
            'cms' => $this->container->getParameter('cms')
        );
    }
    
    public function getName()
    {
        return 'config_expose_extension';
    }
}
