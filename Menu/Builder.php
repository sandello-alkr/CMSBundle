<?php
namespace alkr\CMSBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function breadcrumbs(FactoryInterface $factory, array $options)
    {
        $twig = $this->container->get('twig');
        $globals = $twig->getGlobals();

        $em = $this->container->get('doctrine.orm.entity_manager');
        $menu = $factory->createItem('root');
        $request = $this->container->get('request');
        if($globals['url_by_path'])
        {
            $current = $em->getRepository('CMSBundle:Page')->findOneByPath($request->get('url'));
            if(is_null($current))
                return $menu;
        }
        else
            $current = $em->getRepository('CMSBundle:Page')->findOneByUrl($request->get('url'));
        $path = array('0'=>$current);
        while($parent = $current->getParent())
        {
            $path[] = $parent;
            $current = $parent;
        }
        if(count($path)<2)
            return $menu;
        for($i=count($path)-1;$i>-1;$i--)
        {
            $menu->addChild($path[$i]->getMenuTitle(),array('route'=>'page_show','routeParameters'=>array('url'=>$path[$i]->getPath())));
        }
        // $menu->addChild('Главная', array('route' => 'index'));

        return $menu;
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        $twig = $this->container->get('twig');
        $globals = $twig->getGlobals();

        $file = yaml_parse_file(__DIR__.'/../../../../../../app/config/globals.yml');
        $em = $this->container->get('doctrine.orm.entity_manager');
        $menu = $factory->createItem('root');
        $mainCategory = $em->getRepository('CMSBundle:Category')->find(2);
        foreach ($em->getRepository('CMSBundle:Page')->findBy(array('parent'=>null,'enabled'=>1,'category'=>$mainCategory)) as $page) {
            $menu->addChild($page->getMenuTitle(), array('route' => 'page_show','routeParameters' => array('url'=>$page->getPath())));
        }
        if($file['twig']['globals']['footer']['faq'])
            $menu->addChild('FAQ', array('route' => 'faq'));
        if($file['twig']['globals']['footer']['sitemap'])
            $menu->addChild('Карта сайта', array('route' => 'sitemap'));
        if($file['twig']['globals']['footer']['reviews'])
            $menu->addChild('Отзывы', array('route' => 'review'));

        return $menu;
    }

    public function gallery(FactoryInterface $factory, array $options)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $avalancheService = $this->container->get('imagine.cache.path.resolver');
        $menu = $factory->createItem('root');
        // $menu->addChild('Главная', array('route' => 'index'));
        $photos = $em->createQueryBuilder('p')
            ->select('p')
            ->from('CMSBundle:Photo','p')
            ->where('p.page IS NOT NULL')
            ->getQuery()
            ->getResult();

        foreach ($photos as $photo) {
            $menu->addChild($avalancheService->getBrowserPath($photo->getWebPath(), 'sidebar_thumb'),array('route' => 'page_show','routeParameters' => array('url'=>$photo->getPage()->getUrl())));
        }

        return $menu;
    }

    public function headerCarousel(FactoryInterface $factory, array $options)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $menu = $factory->createItem('root');
        
        $header = $em->getRepository('CMSBundle:Slide')->findAll();
        if(count($header) > 0)
        {
            $avalancheService = $this->container->get('imagine.cache.path.resolver');
            foreach ($header as $slide)
                if(is_object($slide->getPhoto()))
                {
                    $menu->addChild($slide->getTitle(),array('uri' => $slide->getPhoto()->getLink()));
                    $menu[$slide->getTitle()]->setLinkAttribute('data-picture', $avalancheService->getBrowserPath($slide->getPhoto()->getWebPath(), 'my_thumb'));
                    $menu[$slide->getTitle()]->setLinkAttribute('data-content', $slide->getContent());
                }
        }
        // $menu->addChild('Главная', array('route' => 'index'));
        return $menu;
    }

    public function lastPosts(FactoryInterface $factory, array $options)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $avalancheService = $this->container->get('imagine.cache.path.resolver');
        $menu = $factory->createItem('root');
        // $menu->addChild('Главная', array('route' => 'index'));
        $posts = $em->createQueryBuilder('p')
            ->select('p')
            ->from('CMSBundle:Post','p')
            ->where('p.annotation IS NOT NULL')
            ->orderBy('p.date','DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        foreach ($posts as $post) {
            $menu->addChild($post->getTitle(),array('route' => 'post_show','routeParameters' => array('url'=>$post->getUrl())));
            if(is_object($post->getPhoto()))
                $menu[$post->getTitle()]->setLinkAttribute('data-picture', $avalancheService->getBrowserPath($post->getPhoto()->getWebPath(), 'sidebar_post_thumb'));
            $menu[$post->getTitle()]->setLinkAttribute('data-annotation', $post->getAnnotation());
            $menu[$post->getTitle()]->setLinkAttribute('data-date', $post->getDate()->format('d.m.Y'));
            $menu[$post->getTitle()]->setAttribute('class', 'post');
        }

        return $menu;
    }
}
?>