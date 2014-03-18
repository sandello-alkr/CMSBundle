<?php

namespace alkr\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Page
 *
 * @Gedmo\Tree(type="materializedPath")
 * @Gedmo\TranslationEntity(class="alkr\CMSBundle\Entity\PageTranslation")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\MaterializedPathRepository")
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
     * @Gedmo\TreePath(separator="/",appendId=false)
     * @ORM\Column(name="path", type="string", length=3000, nullable=true)
     */
    private $path;

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
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"title"})
     * @Gedmo\TreePathSource
     * @ORM\Column(name="url", type="string", length=100)
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
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    private $lvl;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="alkr\CMSBundle\Entity\Category", inversedBy="pages")
     * @ORM\JoinColumn(name="category", referencedColumnName="id" )
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="alkr\CMSBundle\Entity\Photo", mappedBy="page", cascade={"remove","persist"})
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
     * @ORM\Column(name="annotation", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $annotation;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Gedmo\Translatable
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     * @Gedmo\Translatable
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="metaTitle", type="string", length=40, nullable=true)
     * @Gedmo\Translatable
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @ORM\OneToMany(
     *   targetEntity="PageTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * Required for Translatable behaviour
     * @Gedmo\Locale
     */
    protected $locale;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lastmod = new \DateTime();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation($t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation($t)
    {
        $this->translations->removeElement($t);
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
     * @param \alkr\CMSBundle\Entity\Page $children
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
     * Get view
     *
     * @return string 
     */
    public function getView()
    {
        return is_object($this->category)?$this->category->getView():'';
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
        return $this->enabled;
    }

    /**
     * Set category
     *
     * @param \alkr\CMSBundle\Entity\Category $category
     * @return Page
     */
    public function setCategory(\alkr\CMSBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \alkr\CMSBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
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

    public function getCategoryName()
    {
        return is_object($this->category)?$this->category->getName():'';
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

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}