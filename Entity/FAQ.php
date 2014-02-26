<?php

namespace alkr\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FAQ
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FAQ
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
     * @ORM\Column(name="question", type="text")
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text")
     */
    private $answer;

    /**
     * @var integer
     *
     * @ORM\Column(name="prior", type="integer")
     */
    private $prior;

    function __construct() {
        $this->prior = 0;
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
     * Set question
     *
     * @param string $question
     * @return FAQ
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     * @return FAQ
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set prior
     *
     * @param integer $prior
     * @return FAQ
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
}