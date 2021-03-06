<?php

namespace Interne\FichierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Adresse
 *
 * @ORM\Table(name="fichier_adresses")
 * @ORM\Entity(repositoryClass="Interne\FichierBundle\Entity\AdresseRepository")
 */
class Adresse
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
     * @ORM\Column(name="rue", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    private $rue;

    /**
     * @var integer
     *
     * @ORM\Column(name="npa", type="integer", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    private $npa;

    /**
     * @var string
     *
     * @ORM\Column(name="localite", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    private $localite;

    /**
     * @var boolean
     * @ORM\Column(name="facturable", type="boolean")
     */
    private $facturable;
    
    /**
     * @var text
     * 
     * @ORM\Column(name="remarques", type="text", nullable=true)
     */
     private $remarques;


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
     * Set rue
     *
     * @param string $rue
     * @return Adresse
     */
    public function setRue($rue)
    {
        $this->rue = $rue;
    
        return $this;
    }

    /**
     * Get rue
     *
     * @return string 
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set npa
     *
     * @param integer $npa
     * @return Adresse
     */
    public function setNpa($npa)
    {
        $this->npa = $npa;
    
        return $this;
    }

    /**
     * Get npa
     *
     * @return integer 
     */
    public function getNpa()
    {
        return $this->npa;
    }

    /**
     * Set localite
     *
     * @param string $localite
     * @return Adresse
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;
    
        return $this;
    }

    /**
     * Get localite
     *
     * @return string 
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * Set facturable
     *
     * @param boolean $facturable
     * @return Adresse
     */
    public function setFacturable($facturable)
    {
        $this->facturable = $facturable;
    
        return $this;
    }

    /**
     * Get facturable
     *
     * @return boolean 
     */
    public function getFacturable()
    {
        return $this->facturable;
    }

    /**
     * Set remarques
     *
     * @param string $remarques
     * @return Adresse
     */
    public function setRemarques($remarques)
    {
        $this->remarques = $remarques;
    
        return $this;
    }

    /**
     * Get remarques
     *
     * @return string 
     */
    public function getRemarques()
    {
        return $this->remarques;
    }

    public function __toString() {
        return $this->rue.'\n'.$this->npa.' '.$this->localite;
    }

}