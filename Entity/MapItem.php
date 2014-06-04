<?php

namespace alkr\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MapItem
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MapItem
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
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="baloon", type="string", length=255)
     */
    private $baloon;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=20)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=20)
     */
    private $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="mapItems")
     * @ORM\JoinColumn(name="page", referencedColumnName="id" )
     */
    private $page;


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
     * Set address
     *
     * @param string $address
     *
     * @return MapItem
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set baloon
     *
     * @param string $baloon
     *
     * @return MapItem
     */
    public function setBaloon($baloon)
    {
        $this->baloon = $baloon;

        return $this;
    }

    /**
     * Get baloon
     *
     * @return string 
     */
    public function getBaloon()
    {
        return $this->baloon;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return MapItem
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set page
     *
     * @param \alkr\CMSBundle\Entity\Page $page
     *
     * @return MapItem
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
     * Set lat
     *
     * @param string $lat
     *
     * @return MapItem
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     *
     * @return MapItem
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
    }
}
