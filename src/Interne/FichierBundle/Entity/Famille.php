<?php

namespace Interne\FichierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Famille
 *
 * @ORM\Table(name="fichier_familles")
 * @ORM\Entity(repositoryClass="Interne\FichierBundle\Entity\FamilleRepository")
 */
 
class Famille
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
     * @ORM\OneToMany(targetEntity="Interne\FichierBundle\Entity\Membre", mappedBy="famille", cascade={"persist", "remove"})
     */
    private $membres;
    
	
    /**
     * @orm\OneToOne(targetEntity="Interne\FichierBundle\Entity\Adresse", cascade={"persist", "remove"})
     */
    private $adresse;
	
    /**
     * @ORM\OneToOne(targetEntity="Interne\FichierBundle\Entity\Contact", cascade={"persist", "remove"})
     */
    private $contact;

    /**
     * @ORM\OneToOne(targetEntity="Interne\FichierBundle\Entity\Geniteur", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="pere_id", referencedColumnName="id")
     */
    private $pere;

    /**
     * 
     * @ORM\OneToOne(targetEntity="Interne\FichierBundle\Entity\Geniteur", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="mere_id", referencedColumnName="id")
     */
    private $mere;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "2")
     */
    private $nom;
    
    

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
     * Get membres
     *
     * @return array 
     */
    public function getMembres()
    {
        return $this->membres;
    }

    /**
     * Set pere
     *
     * @param \Interne\FichierBundle\Entity\Geniteur
     * @return Famille
     */
    public function setPere( \Interne\FichierBundle\Entity\Geniteur $pere = null)
    {
        $this->pere = $pere;
    
        return $this;
    }

    /**
     * Get pere
     *
     * @return \Interne\FichierBundle\Entity\Geniteur
     */
    public function getPere()
    {
        return $this->pere;
    }

    /**
     * Set mere
     *
     * @param  \Interne\FichierBundle\Entity\Geniteur
     * @return Famille
     */
    public function setMere( \Interne\FichierBundle\Entity\Geniteur $mere = null)
    {
        $this->mere = $mere;
    
        return $this;
    }

    /**
     * Get mere
     *
     * @return \Interne\FichierBundle\Entity\Geniteur
     */
    public function getMere()
    {
        return $this->mere;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Famille
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    
    /**
     * Add membres
     *
     * @param \Interne\FichierBundle\Entity\Membre $membres
     * @return Famille
     */
    public function addMembre(\Interne\FichierBundle\Entity\Membre $membres)
    {
        $this->membres[] = $membres;
    	$membres->setFamille($this);
        return $this;
    }

    /**
     * Remove membres
     *
     * @param \Interne\FichierBundle\Entity\Membre $membres
     */
    public function removeMembre(\Interne\FichierBundle\Entity\Membre $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Set contact
     *
     * @param \Interne\FichierBundle\Entity\Contact $contact
     * @return Famille
     */
    public function setContact(\Interne\FichierBundle\Entity\Contact $contact = null)
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
     * Set adresse
     *
     * @param \Interne\FichierBundle\Entity\Adresse $adresse
     * @return Famille
     */
    public function setAdresse(\Interne\FichierBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return \Interne\FichierBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}