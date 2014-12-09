<?php

namespace Interne\FichierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Interne\FichierBundle\Entity\Geniteur;
use Interne\FactureBundle\Entity\Facture;

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

    /*
     * =========== RELATIONS POUR LES FACTURES===============
     */

    /**
     * @var ArryCollection
     *
     * @ORM\OneToMany(targetEntity="Interne\FactureBundle\Entity\Facture",
     *                mappedBy="famille", cascade={"persist"})
     */
    private $factures;

    /**
     * @var ArryCollection
     *
     * @ORM\OneToMany(targetEntity="Interne\FactureBundle\Entity\Creance",
     *                mappedBy="famille", cascade={"persist"})
     */
    private $creances;



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
        $this->nom = ucwords($nom);
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return ucwords($this->nom);
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

    /**
     * Doit renvoyer quelque chose qui permet d'identifier (humainement) une famille
     * Le nom n'est pas suffisant p.ex puisqu'il peut y avoir plusieurs famille avec le même nom
     *
     * @return string
     */
    public function __toString() {
        return "Les " . $this->getNom() . " de " . $this->getAdresse()->getLocalite(); // . " (" . sizeof($this->getMembres()) . ")";
    }

    /**
     * Set facture
     *
     * @param ArrayCollection $factures
     * @return Famille
     */
    public function setFacture(ArrayCollection $factures)
    {
        $this->factures = $factures;

        foreach($factures as $facture)
        {
            $facture->setFamille($this);
        }

        return $this;
    }

    /**
     * Get facture
     *
     * @return ArrayCollection
     */
    public function getFactures()
    {
        return $this->factures;
    }

    /**
     * Add facture
     *
     * @param Facture $facture
     * @return Famille
     */
    public function addFacture($facture)
    {
        $this->factures[] = $facture;
        $facture->setFamille($this);

        return $this;
    }

    /**
     * Remove facture
     *
     * @param Facture $facture
     * @return Famille
     */
    public function removeFacture($facture)
    {
        $this->factures->remove($facture);
        $facture->setFamille(null);

        return $this;
    }

    /**
     * Add creance
     *
     * @param Creance $creance
     * @return Famille
     */
    public function addCreance($creance)
    {
        $this->creances[] = $creance;
        $creance->setFamille($this);

        return $this;
    }

    /**
     * Remove creance
     *
     * @param Creance $creance
     * @return Famille
     */
    public function removeCreance($creance)
    {
        $this->creances->remove($creance);
        $creance->setFamille(null);

        return $this;
    }

    /**
     * Set creances
     *
     * @param ArrayCollection $creances
     * @return Famille
     */
    public function setCreances(ArrayCollection $creances)
    {
        $this->creances = $creances;

        foreach($creances as $creance)
        {
            $creance->setFamille($this);
        }

        return $this;
    }

    /**
     * Get creances
     *
     * @return ArrayCollection
     */
    public function getCreances()
    {
        return $this->creances;
    }

    /**
     * Is classe
     *
     * @param string $className
     * @return boolean
     */
    public function isClass($className)
    {
        if($className == 'Famille')
            return true;
        else
            return false;
    }

}