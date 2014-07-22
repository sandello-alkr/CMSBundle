<?php

namespace alkr\CMSBundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

class PageListener {

    protected $children = array();

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof \alkr\CMSBundle\Entity\Page) {
            if ($eventArgs->hasChangedField('enabled') && $eventArgs->getNewValue('enabled') == false) {
                $em = $eventArgs->getObjectManager();
                foreach ($em->getRepository('CMSBundle:Page')->getChildren($entity,true) as $child) {
                    $child->setEnabled(false);
                    $this->children[] = $child;
                }
            }
            if ($eventArgs->hasChangedField('url')) {
                $em = $eventArgs->getObjectManager();

                $returnValue = preg_replace('/(glavnaia\/|glavnaia)(.+)/', '$2', $entity->getUrl(), -1, $count);
                if($count > 0)
                    $entity->setUrl($returnValue);

                foreach ($em->getRepository('CMSBundle:Page')->getChildren($entity,true) as $child) {
                    $returnValue = preg_replace('/(.+)\/([^\/]+)/', '$2', $child->getUrl(), -1, $count);
                    $child->setUrl($returnValue);
                    $this->children[] = $child;
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $event)
    {
        if(count($this->children)>0) {
            $em = $event->getEntityManager();
            foreach ($this->children as $child) {
                $em->persist($child);
            }
            $this->children = array();
            $em->flush();
        }
    }
}