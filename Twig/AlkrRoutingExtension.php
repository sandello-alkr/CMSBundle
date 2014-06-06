<?php
namespace alkr\CMSBundle\Twig;
 
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Doctrine\ORM\EntityManager;
 
class AlkrRoutingExtension extends RoutingExtension
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    protected $top_menu_parent;

    function __construct(UrlGeneratorInterface $generator, EntityManager $em, $top_menu_parent)
    {
        $this->top_menu_parent = $top_menu_parent;
        $this->em = $em;
        parent::__construct($generator);
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function getPath($name, $parameters = array(), $relative = false)
    {
        $cut_path = $this->em->getRepository('CMSBundle:Page')->find($this->top_menu_parent);
        return str_replace(urlencode($cut_path->getUrl()).'/', '', parent::getPath($name, $parameters, $relative));
    }
}