<?php

namespace alkr\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Redirect
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Redirect
{
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
     * @ORM\Column(name="oldUrl", type="string", length=255)
     */
    private $oldUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="newUrl", type="string", length=255)
     */
    private $newUrl;

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
     * Set newUrl
     *
     * @param string $newUrl
     * @return Redirect
     */
    public function setNewUrl($newUrl)
    {
        $this->newUrl = $newUrl;
    
        return $this;
    }

    /**
     * Get newUrl
     *
     * @return string 
     */
    public function getNewUrl()
    {
        return $this->newUrl;
    }

    /**
     * Set oldUrl
     *
     * @param string $oldUrl
     * @return Redirect
     */
    public function setOldUrl($oldUrl)
    {
        $this->oldUrl = $oldUrl;
    
        return $this;
    }

    /**
     * Get oldUrl
     *
     * @return string 
     */
    public function getOldUrl()
    {
        return $this->oldUrl;
    }
}