<?php

namespace alkr\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlockController extends Controller
{
    public function newsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('CMSBundle:Post')->findBy(array(),array('date'=>'DESC'),$request->get('max',2),$request->get('offset',0));

        return $this->render(
            'CMSBundle:Block:blocks.html.twig',
            array(
                'items' => $posts,
                'options'=>array(
                    'class'     =>  explode(' ', $request->get('class','')),
                    'type'      =>  $request->get('type','content'),
                    'carousel'  =>  $request->get('carousel',null),
                    'slides'  =>  $request->get('slides',null)
                    )
                )
            );
    }

    public function bannersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $banners = $em->getRepository('CMSBundle:Banner')->findBy(array(),array(),$request->get('max',2),$request->get('offset',0));
        return $this->render(
            'CMSBundle:Block:blocks.html.twig',
            array(
                'items' => $banners,
                'options'=>array(
                    'class'     =>  explode(' ', $request->get('class','')),
                    'type'      =>  $request->get('type','gallery'),
                    'carousel'  =>  $request->get('carousel',null),
                    'slides'  =>  $request->get('slides',null)
                    )
                )
            );
    }

    public function sliderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository('CMSBundle:Slide')->findAll();

        return $this->render(
            'CMSBundle:Block:slider.html.twig',
            array(
                'slides' => $slides, 'id'=>$request->get('id',rand()), 'filter'=>$request->get('filter','my_thumb')
                )
            );
    }

    /**
     * @Route("/gallery/image",name="gallery_image")
     * @Template()
     */
    public function galleryImageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->createQueryBuilder('p')
            ->from('CMSBundle:Photo','p')
            ->join('p.page','page')
            ->join('page.category','cat')
            ->where('cat.id = 3')
            ->select('count(p)');
        $count = $image
            ->getQuery()
            ->getSingleResult();
        $image = $image
            ->select('p')
            ->setFirstResult(rand(0, $count[1] - 1))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $avalancheService = $this->container->get('imagine.cache.path.resolver');
        return new Response(json_encode(array(
            'path'=>$avalancheService->getBrowserPath($image->getWebPath(), 'sidebar_thumb'),
            'href'=>$this->generateUrl('page_show', array(
                'url'=>$image->getPage()->getUrl()
                ))
            )));
    }

    /**
     * @Template()
     */
    public function leftMenuAction($url = null)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CMSBundle:Page');
        $pages = $repo->getChildren($repo->find($this->container->getParameter('left_menu_parent')),true,'prior');

        return array('pages'=>$pages,'repo'=>$repo);
    }

    /**
     * @Template()
     */
    public function mainMenuAction($url = null)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CMSBundle:Page');
        $pages = $repo->getChildren($repo->find($this->container->getParameter('top_menu_parent')),true,'prior');

        return array('pages'=>$pages,'repo'=>$repo);
    }
}
