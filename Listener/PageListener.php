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