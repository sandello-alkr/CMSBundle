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
    protected $excludes;
    protected $replace;

    function __construct(UrlGeneratorInterface $generator, EntityManager $em, $excludes)
    {
        $this->excludes = $excludes;
        $this->em = $em;
        if(!isset($this->replace))
            $this->getReplaces();
        parent::__construct($generator);
    }

    public function getReplaces()
    {
        $cut_paths = $this->em->createQueryBuilder('p')
            ->select('p.url')
            ->from('CMSBundle:Page','p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids',$this->excludes)
            ->getQuery()
            ->getResult();

        $replace = array();
        foreach ($cut_paths as $value) {
            $replace[] = $value['url'].'/';
        }
        $this->replace = $replace;
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function getPath($name, $parameters = array(), $relative = false)
    {
        return str_replace($this->replace, '', parent::getPath($name, $parameters, $relative));
    }
}