<?php

namespace Interne\GlobalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modification
 *
 * @ORM\Table(name="global_modifications")
 * @ORM\Entity(repositoryClass="Interne\GlobalBundle\Entity\ModificationRepository")
 */
class Modification
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
     * @ORM\Column(name="champ", type="string", length=255)
     */
    private $champ;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="text")
     */
    private $valeur;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text")
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Interne\GlobalBundle\Entity\Validation", inversedBy="modifications")
     */
    private $validation;

    /**
     * @ORM\ManyToOne(targetEntity="Interne\SecurityBundle\Entity\User")
     */
    private $user;




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
     * Set valeur
     *
     * @param string $valeur
     * @return Modification
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string 
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Modification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set champ
     *
     * @param string $champ
     * @return Modification
     */
    public function setChamp($champ)
    {
        $this->champ = $champ;

        return $this;
    }

    /**
     * Get champ
     *
     * @return string 
     */
    public function getChamp()
    {
        return $this->champ;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Modification
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set validation
     *
     * @param \Interne\GlobalBundle\Entity\Validation $validation
     * @return Modification
     */
    public function setValidation(\Interne\GlobalBundle\Entity\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return \Interne\GlobalBundle\Entity\Validation 
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set user
     *
     * @param \Interne\SecurityBundle\Entity\User $user
     * @return Modification
     */
    public function setUser(\Interne\SecurityBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Interne\SecurityBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
