<?php

namespace alkr\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
{

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="view", type="string", length=50)
     */
    private $view;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="predefined", type="boolean")
     */
    private $predefined;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="category")
     * @var ArrayCollection $pages
     */
    private $pages;

    function __construct($name = false, $predefined = false) {
        if($name)
            $this->name = $name;
        $this->predefined = $predefined;
        $this->view = 'one_sidebar.html.twig';
    }

    public function getTopPages()
    {
        $pages = array();
        foreach ($this->pages as $page) {
            if(!is_object($page->getParent()) && $page->getEnabled())
                $pages[] = $page;
        }
        return $pages;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pages
     *
     * @param string $pages
     * @return Category
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    
        return $this;
    }

    /**
     * Get pages
     *
     * @return string 
     */
    public function getPages()
    {
        return $this->pages;
    }
    
    /**
     * Set view
     *
     * @param string $view
     * @return Category
     */
    public function setView($view)
    {
        $this->view = $view;
    
        return $this;
    }

    /**
     * Get view
     *
     * @return string 
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Add pages
     *
     * @param \alkr\CMSBundle\Entity\Page $pages
     * @return Category
     */
    public function addPage(\alkr\CMSBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    
        return $this;
    }

    /**
     * Remove pages
     *
     * @param \alkr\CMSBundle\Entity\Page $pages
     */
    public function removePage(\alkr\CMSBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Set predefined
     *
     * @param boolean $predefined
     * @return Category
     */
    public function setPredefined($predefined)
    {
        $this->predefined = $predefined;
    
        return $this;
    }

    /**
     * Get predefined
     *
     * @return boolean 
     */
    public function getPredefined()
    {
        return $this->predefined;
    }
}