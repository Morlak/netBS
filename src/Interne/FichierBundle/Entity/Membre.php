<?php

namespace Interne\FichierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Interne\FichierBundle\Entity\Personne;

/**
 * Membre
 *
 * @ORM\Table(name="fichier_membres")
 * @ORM\Entity(repositoryClass="Interne\FichierBundle\Entity\MembreRepository")
 */
class Membre extends Personne
{
	
	public function __construct() {
		
		$this->inscription = new \Datetime();
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
     * @ORM\ManyToOne(targetEntity="Interne\FichierBundle\Entity\Famille", inversedBy="membres")
     * @ORM\JoinColumn(name="famille_id", referencedColumnName="id")
     */
    private $famille;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Interne\StructureBundle\Entity\Attribution", mappedBy="membre", cascade={"persist", "remove"})
     */
    private $attributions;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Interne\StructureBundle\Entity\ObtentionDistinction", mappedBy="membre", cascade={"persist", "remove"})
     */
    private $distinctions;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="naissance", type="date")
     */
     private $naissance;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_bs", type="integer", nullable=true)
     */
    private $numeroBs;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_avs", type="string", length=255, nullable=true)
     */
    private $numeroAvs;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity="Interne\FichierBundle\Entity\Contact", cascade={"persist", "remove"})
     */
    private $contact;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="inscription", type="date")
     */
    private $inscription;

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
     * Set famille
     *
     * @param integer $famille
     * @return Membre
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;
    
        return $this;
    }

    /**
     * Get famille
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set distinctions
     *
     * @param array $distinctions
     * @return Membre
     */
    public function setDistinctions($distinctions)
    {
        $this->distinctions = $distinctions;
    
        return $this;
    }

    /**
     * Get distinctions
     *
     * @return array 
     */
    public function getDistinctions()
    {
        return $this->distinctions;
    }

    /**
     * Set numeroBs
     *
     * @param integer $numeroBs
     * @return Membre
     */
    public function setNumeroBs($numeroBs)
    {
        $this->numeroBs = $numeroBs;
    
        return $this;
    }

    /**
     * Get numeroBs
     *
     * @return integer 
     */
    public function getNumeroBs()
    {
        return $this->numeroBs;
    }

    /**
     * Set numeroAvs
     *
     * @param string $numeroAvs
     * @return Membre
     */
    public function setNumeroAvs($numeroAvs)
    {
        $this->numeroAvs = $numeroAvs;
    
        return $this;
    }

    /**
     * Get numeroAvs
     *
     * @return string 
     */
    public function getNumeroAvs()
    {
        return $this->numeroAvs;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Membre
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set contact
     *
     * @param \stdClass $contact
     * @return Membre
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return \stdClass 
     */
    public function getContact()
    {
        return $this->contact;
    }
    

    /**
     * Set naissance
     *
     * @param \DateTime $naissance
     * @return Membre
     */
    public function setNaissance($naissance)
    {
        $this->naissance = new \Datetime($naissance);
    
        return $this;
    }

    /**
     * Get naissance
     *
     * @return \DateTime 
     */
    public function getNaissance()
    {
        return $this->naissance;
    }

    /**
     * Set inscription
     *
     * @param \DateTime $inscription
     * @return Membre
     */
    public function setInscription($inscription)
    {
        $this->inscription = $inscription;
    
        return $this;
    }

    /**
     * Get inscription
     *
     * @return \DateTime 
     */
    public function getInscription()
    {
        return $this->inscription;
    }


    /**
     * Add attributions
     *
     * @param \Interne\StructureBundle\Entity\Attribution $attributions
     * @return Membre
     */
    public function addAttribution(\Interne\StructureBundle\Entity\Attribution $attributions)
    {
        $this->attributions[] = $attributions;
	$attributions->setMembre($this);
        return $this;
    }

    /**
     * Remove attributions
     *
     * @param \Interne\StructureBundle\Entity\Attribution $attributions
     */
    public function removeAttribution(\Interne\StructureBundle\Entity\Attribution $attributions)
    {
        $this->attributions->removeElement($attributions);
    }

    /**
     * Get attributions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributions()
    {
        return $this->attributions;
    }

    /**
     * Set adresse
     *
     * @param \Interne\FichierBundle\Entity\Adresse $adresse
     * @return Membre
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
    

    /**
     * Add distinction
     *
     * @param \Interne\StructureBundle\Entity\ObtentionDistinction $distinction
     * @return Membre
     */
    public function addDistinction(\Interne\StructureBundle\Entity\ObtentionDistinction $distinction)
    {
        $this->distinctions[] = $distinction;
	$distinction->setMembre($this);
        return $this;
    }

    /**
     * Remove distinction
     *
     * @param \Interne\StructureBundle\Entity\ObtentionDistinction $distinction
     */
    public function removeDistinction(\Interne\StructureBundle\Entity\ObtentionDistinction $distinction)
    {
        $this->distinctions->removeElement($distinction);
    }
}
