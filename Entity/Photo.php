<?php

namespace alkr\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Photo
{
    public function __toString() {
        return null === $this->getWebPath() ? "" : $this->getWebPath();
    }

    public function getAbsolutePath()
    {
        return null === $this->filePath ? null : $this->getUploadRootDir().'/'.$this->filePath;
    }

    public function getWebPath()
    {
        return null === $this->filePath ? null : $this->getUploadDir().'/'.$this->filePath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../../../web'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return '/uploads/files';
    }

    public function upload()
    {   
        if (null === $this->file) {
            return;
        }
        $mime = explode('/', $this->file->getMimeType());
        // print_r ($mime);
        // die();
        if ($mime[0] !== 'image') {
            return false;
        }

        if ($this->filePath) {
            unlink($file = $this->getAbsolutePath());
        }

        $name = substr($this->file->getClientOriginalName(), 0, strrpos($this->file->getClientOriginalName(), '.')).mb_strtolower(substr($this->file->getClientOriginalName(), strrpos($this->file->getClientOriginalName(), '.')));
        print_r ($name);
        // die();
        $this->filePath = substr(md5($name.time()), 0, 16).$name;
        $this->file->move($this->getUploadRootDir(), $this->filePath);

        /*$width = 800;
        $height = 800;

        // получение новых размеров
        list($width_orig, $height_orig) = getimagesize($this->getAbsolutePath());

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
           $width = $height*$ratio_orig;
        } else {
           $height = $width/$ratio_orig;
        }

        // ресэмплирование
        $image_p = imagecreatetruecolor($width, $height);
        $imgBlob = file_get_contents($this->getAbsolutePath());
        $image = imagecreatefromstring($imgBlob);
        if($width_orig>$width || $height_orig>$height)
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        else
            $image_p = $image;
        $watermark = imagecreatefrompng(__DIR__."/../../../../../../web/watermark.png");
        imagecopy($image_p, $watermark, 0, 0, 0, 0, 171, 55);
        imagejpeg($image_p, $this->getAbsolutePath(), 100);*/
        return true;
    }

    public function remove()
    {
        if ($this->filePath) {
            if(file_exists($this->getAbsolutePath()))
                unlink($file = $this->getAbsolutePath());
        }
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
     * @Assert\Image(maxSize="6000000")
     */
    public $file;

    /**
     * @var string
     *
     * @ORM\Column(name="filePath", type="string", length=255)
     */
    private $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="Banner", inversedBy="photo")
     */
    private $banner;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="photos")
     * @ORM\JoinColumn(name="page", referencedColumnName="id" )
     */
    private $page;

    /**
     * @ORM\OneToOne(targetEntity="Post", inversedBy="photo")
     */
    private $post;

    /**
     * @ORM\OneToOne(targetEntity="Slide", inversedBy="photo")
     */
    private $slide;

    /**
     * @var integer
     *
     * @ORM\Column(name="prior", type="integer", nullable=true)
     */
    private $prior;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filePath = '';
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
     * Set filePath
     *
     * @param string $filePath
     * @return Photo
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    
        return $this;
    }

    /**
     * Get filePath
     *
     * @return string 
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set prior
     *
     * @param integer $prior
     * @return Photo
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
        return $this->prior;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Photo
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Photo
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
     * Set page
     *
     * @param \alkr\CMSBundle\Entity\Page $page
     * @return Photo
     */
    public function setPage(\alkr\CMSBundle\Entity\Page $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return \alkr\CMSBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set post
     *
     * @param \alkr\CMSBundle\Entity\Post $post
     * @return Photo
     */
    public function setPost(\alkr\CMSBundle\Entity\Post $post = null)
    {
        $this->post = $post;
    
        return $this;
    }

    /**
     * Get post
     *
     * @return \alkr\CMSBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set slide
     *
     * @param \alkr\CMSBundle\Entity\Slide $slide
     * @return Photo
     */
    public function setSlide(\alkr\CMSBundle\Entity\Slide $slide = null)
    {
        $this->slide = $slide;
    
        return $this;
    }

    /**
     * Get slide
     *
     * @return \alkr\CMSBundle\Entity\Slide 
     */
    public function getSlide()
    {
        return $this->slide;
    }

    /**
     * Set banner
     *
     * @param \alkr\CMSBundle\Entity\Banner $banner
     * @return Photo
     */
    public function setBanner(\alkr\CMSBundle\Entity\Banner $banner = null)
    {
        $this->banner = $banner;
    
        return $this;
    }

    /**
     * Get banner
     *
     * @return \alkr\CMSBundle\Entity\Banner 
     */
    public function getBanner()
    {
        return $this->banner;
    }
}