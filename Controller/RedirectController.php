<?php

namespace alkr\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use alkr\CMSBundle\Entity\Redirect;
use alkr\CMSBundle\Form\RedirectType;

/**
 * Redirect controller.
 *
 * @Route("/manager/redirect")
 */
class RedirectController extends Controller
{

    /**
     * Lists all Redirect entities.
     *
     * @Route("/", name="manager_redirect")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CMSBundle:Redirect')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Redirect entity.
     *
     * @Route("/", name="manager_redirect_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity = new Redirect();
        $entity->setOldUrl($request->get('oldUrl'))
            ->setNewUrl($request->get('newUrl'))
            ;
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('manager_redirect'));
    }

    /**
     * Edits an existing Redirect entity.
     *
     * @Route("/{id}", name="manager_redirect_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSBundle:Redirect')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Redirect entity.');
        }

        $entity->setOldUrl($request->get('oldUrl'))
            ->setNewUrl($request->get('newUrl'))
            ;

        $em->flush();

        return $this->redirect($this->generateUrl('manager_redirect'));
    }

    /**
     * Deletes a Redirect entity.
     *
     * @Route("/{id}/delete", name="manager_redirect_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSBundle:Redirect')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Redirect entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('manager_redirect'));
    }
}
