<?php

namespace alkr\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use alkr\CMSBundle\Form\FeedbackType;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CMSBundle:Page')->findBy(array('parent'=>null));
        $posts = $em->getRepository('CMSBundle:Post')->findAll();

        return array(
            'news'          =>  $posts,
            'categories'    => $categories,
            'template'      => 'two_sidebars.html.twig'
            );
    }

    /**
     * @Route("/test/",name="test")
     * @Template()
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CMSBundle:Page')->findBy(array('parent'=>null));
        $posts = $em->getRepository('CMSBundle:Post')->findAll();

        return array(
            'news'          =>  $posts,
            'categories'    => $categories,
            // 'template'      => 'full_width.html.twig'
            );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Template()
     */
    public function showPageAction($url)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSBundle:Page')->findOneByUrl($url);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $return = array('entity'=>$entity);

        if($entity->getFeedback())
        {
            $form = $this->createForm(new FeedbackType(), null, array(
                'action' => $this->generateUrl('send_message'),
                'method' => 'POST',
                'attr'  =>  array('class'=>'feedback_form')
            ));

            $return['form'] = $form->createView();
        }

        if($entity->getMap())
        {
            $return['settings'] = array(
                'address' => $this->container->getParameter('address'),
                'baloon' => $this->container->getParameter('baloon'),
                'baloon_text' => $this->container->getParameter('baloon_text'),
                );
            $return['map'] = true;
        }

        return $return;
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Template()
     */
    public function showPostAction($url)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSBundle:Post')->findOneByUrl($url);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        return array('entity'=>$entity);
    }

    /**
     * @Route("/search/",name="search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $finder = $this->container->get('fos_elastica.finder.pages.page');
        $boolQuery = new \Elastica\Query\Bool();
        $boolQuery->addShould(new \Elastica\Query\Fuzzy('title', $request->get('q')));
        $boolQuery->addShould(new \Elastica\Query\Fuzzy('content', $request->get('q')));
        // $fuzzy->addField('content', array('value'=>$request->get('q')));
        $pages = $finder->find($boolQuery);

        /** var array of Acme\UserBundle\Entity\User limited to 10 results */
        // $users = $finder->find('bob', 10);

        return array(
            'pages'    => $pages,
            );
    }

    /**
     * @Route("/faq/",name="faq")
     * @Template()
     */
    public function faqAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $faq = $em->getRepository('CMSBundle:FAQ')->findAll();

        return array('faq'     => $faq);
    }

    /**
     * @Route("/send/",name="send_message")
     * @Method("POST")
     * @Template()
     */
    public function sendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $transport = $this->container->getParameter('mailer_transport');
        $password = $this->container->getParameter('mailer_password');
        $email = $this->container->getParameter('mailer_user');
        $message = \Swift_Message::newInstance()
            ->setSubject('Новое сообщение')
            ->setFrom(array($email->getValue()=>$request->get('name')))
            ->setTo($email->getValue())
            ->setBody(
                '<p>'.$request->get('message').'</p><p>Контакты: '.$request->get('email').' '.$request->get('contacts').'</p>','text/html'
            );
        $transport = \Swift_SmtpTransport::newInstance($transport->getValue(), 465,'ssl')
            ->setUsername($email->getValue())
            ->setPassword($password->getValue());
        $mailer = \Swift_Mailer::newInstance($transport);
        $mailer->send($message);

        $this->get('session')->getFlashBag()->add('alert-success', 'Сообщение отправлено.');
        /** var array of Acme\UserBundle\Entity\User limited to 10 results */
        // $users = $finder->find('bob', 10);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/manager/",name="manager_index")
     * @Template()
     */
    public function managerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CMSBundle:Page');
        print_r ($repo->getNodesHierarchy());
        return array(
            'repo' => $repo,
            'categories' => $em->getRepository('CMSBundle:Category')->findAll()
            );
    }

    /**
     * @Route("/карта-сайта/",name="sitemap")
     * @Template()
     */
    public function sitemapAction()
    {
        $em = $this->getDoctrine()->getManager();
        return array(
            'categories' => $em->getRepository('CMSBundle:Category')->findAll()
            );
    }

    /**
     * @Route("/sitemap.{_format}", name="sample_sitemaps_sitemap", Requirements={"_format" = "xml"})
     * @Template("CMSBundle::sitemap.xml.twig")
     */
    public function sitemapGenerationAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $urls = array();
        $hostname = $this->getRequest()->getHost();

        // add some urls homepage
        $urls[] = array('loc' => $this->get('router')->generate('index'), 'changefreq' => 'weekly', 'priority' => '1.0');
        $urls[] = array('loc' => $this->get('router')->generate('search'), 'changefreq' => 'never', 'priority' => '0.1');
        $urls[] = array('loc' => $this->get('router')->generate('faq'), 'changefreq' => 'monthly', 'priority' => '0.7');

        // urls from database

        foreach ($em->getRepository('CMSBundle:Page')->findAll() as $page) {
            $urls[] = array('loc' => $this->get('router')->generate('page_show', array('url' => $page->getUrl())), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => $page->getLastmod()->format('Y-m-d'));
        }

        foreach ($em->getRepository('CMSBundle:Post')->findAll() as $post) {
            $urls[] = array('loc' => $this->get('router')->generate('post_show', array('url' => $post->getUrl())), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => $post->getDate()->format('Y-m-d'));
        }

        return array('urls' => $urls, 'hostname' => $hostname);
    }
}
