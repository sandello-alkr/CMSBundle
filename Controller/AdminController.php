<?php

namespace alkr\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * Admin controller.
 */
class AdminController extends Controller
{

    /**
     * @Route("/admin/", name="admin_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        // $file = yaml_parse_file(__DIR__.'/../../../../../../app/config/globals.yml');
        // if($request->getMethod() == 'POST')
        // {
        //     // print_r ($request);
        //     foreach ($file['twig']['globals'] as $place => $vars) {
        //         foreach ($vars as $name => $val) {
        //             if(count($val)>1)
        //             {
        //                 foreach ($val as $name1 => $val1) {
        //                     if($request->get($place.'_'.$name.'_'.$name1,false))
        //                         $file['twig']['globals'][$place][$name][$name1] = true;
        //                     else
        //                         $file['twig']['globals'][$place][$name][$name1] = false;
        //                 }
        //             }
        //             else
        //             {
        //                 if($request->get($place.'_'.$name,false))
        //                     $file['twig']['globals'][$place][$name] = true;
        //                 else
        //                     $file['twig']['globals'][$place][$name] = false;
        //             }
        //         }
        //     }
        //     yaml_emit_file(__DIR__.'/../../../../../../app/config/globals.yml',$file);
            
        // }

        // $form = $this->createForm(new BannerType(), $entity, array(
        //     'action' => $this->generateUrl('manager_banner_update', array('id' => $entity->getId())),
        //     'method' => 'PUT',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Update'));
        $em = $this->getDoctrine()->getManager();
        $banners = $em->getRepository('CMSBundle:Banner')->findAll();

        return array(
            'banners'  =>  $banners,
            'globals'   =>  $file['twig']['globals']
        );
    }


    /**
     * @Route("/admin/update", name="admin_update_header")
     * @Method("PUT")
     */
    public function updateHeaderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSBundle:Banner')->findOneByTitle('Шапка');
        $editForm = $this->createFormBuilder($entity)
            ->add(
                'photos',
                'bootstrap_collection',
                array(
                    'type'               => new \alkr\CMSBundle\Form\PhotoType(),
                    'allow_add'          => true,
                    'allow_delete'       => true,
                    'add_button_text'    => 'Добавить Фото',
                    'delete_button_text' => 'Удалить',
                    'sub_widget_col'     => 9,
                    'button_col'         => 3
                )
            )
            ->add('submit', 'submit', array('label' => 'Создать'))
            ->setMethod('PUT')
            ->setAction($this->generateUrl('admin_update_header'))
            ->getForm();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $add = $entity->getPhotos()->getInsertDiff();
            foreach ($add as $photo) {
                $photo->setPage($entity);
                $photo->upload();
                $em->persist($photo);
            }
            $del = $entity->getPhotos()->getDeleteDiff();
            foreach ($del as $photo) {
                $entity->removePhoto($photo);
                $em->remove($photo);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('admin_index'));
        }

        return array();
    }

    /**
     * @Route("/admin/variables/", name="admin_variables")
     * @Template()
     */
    public function variablesAction(Request $request)
    {
        $txt_file    = file_get_contents(__DIR__.'/../Resources/less/variables.less');
        if($request->getMethod() == 'POST')
        {
            $pattern = array();
            $replacement = array();
            foreach (array_filter($request->request->all()) as $key => $value) {
                $pattern[] = '/(@'.$key.':\s*) (.+);/';
                $replacement[] = '\1 '.$value.';';
            }
            $txt_file = preg_replace($pattern, $replacement, $txt_file);
            file_put_contents(__DIR__.'/../Resources/less/variables.less', $txt_file);

            $application = new Application($this->get('kernel'));
            $application->setAutoExit(false); 

            // The input interface should contain the command name, and whatever arguments the command needs to run      
            // $input = new ArrayInput(array("assetic:dump"));

            // Run the command
            // $output = new ConsoleOutput();
        }
        preg_match_all('/@(\S+):\s+(.+);/', $txt_file, $array, PREG_SET_ORDER);
        $variables = array();
        foreach ($array as $variable) {
            $variables[$variable[1]] = $variable[2];
        }

        return array(
            'variables' =>  $variables,
        );
    }
}