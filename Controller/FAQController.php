<?php

namespace alkr\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use alkr\CMSBundle\Entity\FAQ;
use alkr\CMSBundle\Form\FAQType;

/**
 * FAQ controller.
 *
 * @Route("/manager/faq")
 */
class FAQController extends Controller
{

    /**
     * Lists all FAQ entities.
     *
     * @Route("/", name="manager_faq")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CMSBundle:FAQ')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FAQ entity.
     *
     * @Route("/", name="manager_faq_create")
     * @Method("POST")
     * @Template("CMSBundle:FAQ:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new FAQ();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('manager_faq'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a FAQ entity.
    *
    * @param FAQ $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(FAQ $entity)
    {
        $form = $this->createForm(new FAQType(), $entity, array(
            'action' => $this->generateUrl('manager_faq_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Создать'));

        return $form;
    }

    /**
     * Displays a form to create a new FAQ entity.
     *
     * @Route("/new", name="manager_faq_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FAQ();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FAQ entity.
     *
     * @Route("/{id}", name="manager_faq_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FAQ entity.
     *
     * @Route("/{id}/edit", name="manager_faq_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a FAQ entity.
    *
    * @param FAQ $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(FAQ $entity)
    {
        $form = $this->createForm(new FAQType(), $entity, array(
            'action' => $this->generateUrl('manager_faq_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Сохранить'));

        return $form;
    }
    /**
     * Edits an existing FAQ entity.
     *
     * @Route("/{id}", name="manager_faq_update")
     * @Method("PUT")
     * @Template("CMSBundle:FAQ:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('manager_faq'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FAQ entity.
     *
     * @Route("/{id}", name="manager_faq_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CMSBundle:FAQ')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FAQ entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('manager_faq'));
    }

    /**
     * Creates a form to delete a FAQ entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('manager_faq_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить','attr'=>array('type'=>'danger')))
            ->getForm()
        ;
    }
}
