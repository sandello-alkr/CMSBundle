<?php

namespace alkr\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use alkr\CMSBundle\Lib\Globals;

/**
 * Page
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */
class Page
{   
    public function __toString() {
        return $this->getTitle();
    }

    public function getIndent() {
        $name = $this->getTitle();
        for($i=0;$i<$this->lvl;$i++)
            $name = '&nbsp;&nbsp;'.$name;
        return $name;
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
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @var integer
     * @ORM\Column(name="prior", type="integer")
     */
    private $prior;

    /**
     * @var string
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, separator="-", updatable=false, fields={"title"}, unique=true)
     * @ORM\Column(name="url", type="string")
     */
    private $url;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var boolean
     *
     * @ORM\Column(name="feedback", type="boolean", nullable=true)
     */
    private $feedback;

    /**
     * @var boolean
     *
     * @ORM\Column(name="map", type="boolean", nullable=true)
     */
    private $map;

    /**
     * @ORM\OneToMany(targetEntity="alkr\CMSBundle\Entity\MapItem", mappedBy="page", cascade={"remove","persist"})
     * @var ArrayCollection $mapItems
     */
    private $mapItems;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * @ORM\OrderBy({"prior" = "ASC"})
     */
    private $children;

    /**
     * @ORM\OneToOne(targetEntity="alkr\CMSBundle\Entity\Photo", mappedBy="page_preview", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="preview", referencedColumnName="id")
     */
    private $preview;

    /**
     * @ORM\OneToMany(targetEntity="alkr\CMSBundle\Entity\Photo", mappedBy="page", cascade={"remove","persist"})
     * @ORM\OrderBy({"prior" = "ASC"})
     * @var ArrayCollection $photos
     */
    private $photos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastmod", type="datetime")
     */
    private $lastmod;

    /**
     * @var string
     *
     * @ORM\Column(name="view", type="text", nullable=true)
     */
    private $view;

    /**
     * @var string
     *
     * @ORM\Column(name="annotation", type="text", nullable=true)
     */
    private $annotation;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="metaTitle", type="string", nullable=true)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="menuTitle", type="string", length=40, nullable=true)
     */
    private $menuTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lastmod = new \DateTime();
        $this->prior = 1;
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
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add children
     *
     * @return Page
     */
    /*public function addChildren(\alkr\CMSBundle\Entity\Page $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \alkr\CMSBundle\Entity\Page $children
     */
    /*public function removeChildren(\alkr\CMSBundle\Entity\Page $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    /*public function getChildren()
    {
        $children = array();
        foreach ($this->children as $page)
            if($page->getEnabled())
                $children[]=$page;
        return $children;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAllChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \alkr\CMSBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\alkr\CMSBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \alkr\CMSBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set view
     *
     * @param string $view
     * @return Page
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
     * Set url
     *
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Page
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        // if($this->getParent() && !$this->getParent()->getEnabled())
        //     return false;
        return $this->enabled;
    }

    /**
     * Set feedback
     *
     * @param boolean $feedback
     * @return Page
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
    
        return $this;
    }

    /**
     * Get feedback
     *
     * @return boolean 
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Add photos
     *
     * @param \alkr\CMSBundle\Entity\Photo $photos
     * @return Page
     */
    public function addPhoto(\alkr\CMSBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;
    
        return $this;
    }

    /**
     * Remove photos
     *
     * @param \alkr\CMSBundle\Entity\Photo $photos
     */
    public function removePhoto(\alkr\CMSBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set map
     *
     * @param boolean $map
     * @return Page
     */
    public function setMap($map)
    {
        $this->map = $map;
    
        return $this;
    }

    /**
     * Get map
     *
     * @return boolean 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Page
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Page
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lastmod
     *
     * @param \DateTime $lastmod
     * @return Page
     */
    public function setLastmod($lastmod)
    {
        $this->lastmod = $lastmod;
    
        return $this;
    }

    /**
     * Get lastmod
     *
     * @return \DateTime 
     */
    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * Set annotation
     *
     * @param string $annotation
     * @return Page
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
    
        return $this;
    }

    /**
     * Get annotation
     *
     * @return string 
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    
        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    public function getLvl()
    {
        return $this->lvl;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        if(Globals::getUrlByPath())
            return $this->path;
        else
            return $this->url;
    }

    /**
     * Set menuTitle
     *
     * @param string $menuTitle
     * @return Page
     */
    public function setMenuTitle($menuTitle)
    {
        $this->menuTitle = $menuTitle;
    
        return $this;
    }

    /**
     * Get menuTitle
     *
     * @return string 
     */
    public function getMenuTitle()
    {
        if($this->menuTitle)
            return $this->menuTitle;
        else
            return $this->title;
    }

    /**
     * Set prior
     *
     * @param integer $prior
     * @return Page
     */
    public function setPrior($prior)
    {
        $this->prior = $prior;
        return $this;
    }

    /**
     * Get prior
     *
     * @return integer 
     */
    public function getPrior()
    {
        if($this->prior)
            return $this->prior;
    }

        public function getNextPrior()
    {
        $next = 0;
        foreach ($this->children as $page) {
            if($page->getPrior() > $next)
                $next = $page->getPrior();
        }
        return $next+1;
    }

    /**
     * Set preview
     *
     * @param \alkr\CMSBundle\Entity\Photo $preview
     *
     * @return Page
     */
    public function setPreview(\alkr\CMSBundle\Entity\Photo $preview = null)
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * Get preview
     *
     * @return \alkr\CMSBundle\Entity\Photo 
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return Page
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Add mapItems
     *
     * @param \alkr\CMSBundle\Entity\MapItem $mapItems
     *
     * @return Page
     */
    public function addMapItem(\alkr\CMSBundle\Entity\MapItem $mapItems)
    {
        $this->mapItems[] = $mapItems;

        return $this;
    }

    /**
     * Remove mapItems
     *
     * @param \alkr\CMSBundle\Entity\MapItem $mapItems
     */
    public function removeMapItem(\alkr\CMSBundle\Entity\MapItem $mapItems)
    {
        $this->mapItems->removeElement($mapItems);
    }

    /**
     * Get mapItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMapItems()
    {
        return $this->mapItems;
    }

    public function getMapCenter()
    {
        if(count($this->mapItems) == 0)
            return array('lat'=>null,'lng'=>null);
        $array = array('lat'=>0,'lng'=>0);
        foreach ($this->mapItems as $mapItem) {
            $array['lat'] += $mapItem->getLat();
            $array['lng'] += $mapItem->getLng();
        }
        $array['lat']/=count($this->mapItems);
        $array['lng']/=count($this->mapItems);          
        return $array;
    }

    public function getClass()
    {
        preg_match('/.+\\\\(.+)/', get_class($this), $name);
        return mb_strtolower($name[1]);
    }
}
