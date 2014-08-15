<?php

namespace Interne\FichierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Interne\FichierBundle\Entity\Personne;

/**
 * Geniteur
 *
 * @ORM\Table(name="fichier_geniteurs")
 * @ORM\Entity(repositoryClass="Interne\FichierBundle\Entity\GeniteurRepository")
 */
class Geniteur extends Personne
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
     * @ORM\OneToOne(targetEntity="Interne\FichierBundle\Entity\Contact", cascade={"persist", "remove"})
     */
    private $contact;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="profession", type="string", nullable=true)
     */
    private $profession;
    

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
     * Set contact
     *
     * @param \Interne\FichierBundle\Entity\Contact $contact
     * @return Geniteur
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return \Interne\FichierBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set profession
     *
     * @param integer $profession
     * @return Geniteur
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    
        return $this;
    }

    /**
     * Get profession
     *
     * @return integer 
     */
    public function getProfession()
    {
        return $this->profession;
    }
}