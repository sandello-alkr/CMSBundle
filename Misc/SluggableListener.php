<?php
 
namespace alkr\CMSBundle\Misc;
 
class SluggableListener extends \Gedmo\Sluggable\SluggableListener
{
 
    public function __construct(){
        $this->setTransliterator(array('\alkr\CMSBundle\Misc\Transliterator', 'transliterate'));
        $this->setUrlizer(array('\alkr\CMSBundle\Misc\Transliterator', 'urlize'));
    }
 
    protected function getNamespace()
    {
        return parent::getNamespace();
    }
 
 
}