<?php

namespace Interne\FichierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSchema\Constraints\String;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Interne\FichierBundle\Entity\Famille;
use Interne\FactureBundle\Entity\Creance;

/**
 * Membre
 *
 * @ORM\Table(name="fichier_membres")
 * @ORM\Entity(repositoryClass="Interne\FichierBundle\Entity\MembreRepository")
 */
class Membre extends Personne
{

    public function __construct()
    {
        $this->inscription = new \Datetime();
        $this->creances = new ArrayCollection();
        $this->factures = new ArrayCollection();

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
     * @var Famille
     *
     * @ORM\ManyToOne(targetEntity="Interne\FichierBundle\Entity\Famille", inversedBy="membres")
     * @ORM\JoinColumn(name="famille_id", referencedColumnName="id")
     */
    private $famille;

    /*
     * ajouter par uffer
     */

    /*
     * =========== RELATIONS POUR LES FACTURES===============
     */
    /**
     * @var ArryCollection
     *
     * @ORM\OneToMany(targetEntity="Interne\FactureBundle\Entity\Creance",
     *                mappedBy="membre", cascade={"persist","remove"})
     */
    private $creances;
    /**
     * @var ArryCollection
     *
     * @ORM\OneToMany(targetEntity="Interne\FactureBundle\Entity\Facture",
     *                mappedBy="membre", cascade={"persist","remove"})
     */
    private $factures;



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



    /*
     * Ajouter par uffer
     */
    /**
     * @var envoiFacture
     *
     * @ORM\Column(name="envoi_facture", type="string", columnDefinition="ENUM('Famille', 'Membre')")
     *
     */
    private $envoiFacture;


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
     * @var date
     *
     * @ORM\Column(name="inscription", type="date")
     */
    private $inscription;

    /**
     * @var text
     *
     * @ORM\Column(name="remarques", type="text", nullable=true)
     */
    private $remarques;


    /**
     * @var Adresse
     *
     * @ORM\OneToOne(targetEntity="\Interne\FichierBundle\Entity\Adresse", cascade={"persist", "remove"})
     */
    protected $adressePrincipale;


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
     * Set id
     *
     * @param integer $id
     * @return Membre
     */
    public function setId($id) {

        $this->id = $id;
        return $this;
    }

    /**
     * Set famille
     *
     * @param Famille $famille
     * @return Membre
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return Famille
     */
    public function getFamille()
    {
        //if($this->famille == null) $this->famille = new Famille();
        return $this->famille;
    }

    /**
     * Set nom
     *
     * @param Famille $famille
     * @return Membre
     */
    public function setNom($famille)
    {
        $this->setFamille($famille);

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {

        if ($this->getFamille() != null)
            return $this->getFamille()->getNom();
        else
            return "Pas dans une famille...";
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
     * Set naissance
     *
     * @param \DateTime $naissance
     * @return Membre
     */
    public function setNaissance($naissance)
    {
        $this->naissance = $naissance;

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
     * Get active attributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActiveAttributions()
    {
        return $this->attributions; //TODO: filter active attributions
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
     * @param Adresse $adresse
     * @return Membre
     */
    public function setAdressePrincipale(\Interne\FichierBundle\Entity\Adresse $adresse = null)
    {
        $this->adressePrincipale = $adresse;

        return $this;
    }

    /**
     * @return Adresse
     */
    public function getAdressePrincipale()
    {
        if ($this->adressePrincipale != null)
            return $this->adressePrincipale;

        if ($this->getAdresse() != null)
            return $this->adresse;

        if ($this->getFamille()->getAdresse() != null)
            return $this->getFamille()->getAdresse();
    }


    /**
     * Set remarques
     *
     * @param $remarques
     * @return Membre
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


    /**
     * Add creance
     *
     * @param Creance $creance
     * @return Membre
     */
    public function addCreance($creance)
    {
        $this->creances[] = $creance;
        $creance->setMembre($this);

        return $this;
    }

    /**
     * Remove creance
     *
     * @param Creance $creance
     * @return Membre
     */
    public function removeCreance($creance)
    {
        $this->creances->remove($creance);
        $creance->setMembre(null);

        return $this;
    }

    /**
     * Set creances
     *
     * @param ArrayCollection $creances
     * @return Membre
     */
    public function setCreances(ArrayCollection $creances)
    {
        $this->creances = $creances;

        foreach($creances as $creance)
        {
            $creance->setMembre($this);
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
     * Set facture
     *
     * @param ArrayCollection $factures
     * @return Membre
     */
    public function setFacture(ArrayCollection $factures)
    {
        $this->factures = $factures;

        foreach($factures as $facture)
        {
            $facture->setMembre($this);
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
     * @return Membre
     */
    public function addFacture($facture)
    {
        $this->factures[] = $facture;
        $facture->setMembre($this);

        return $this;
    }

    /**
     * Remove facture
     *
     * @param Facture $facture
     * @return Membre
     */
    public function removeFacture($facture)
    {
        $this->factures->remove($facture);
        $facture->setMembre(null);

        return $this;
    }

    /**
     * Set envoiFacture
     *
     * @param string $envoiFacture
     * @return Membre
     */
    public function setEnvoiFacture($envoiFacture)
    {
        $this->envoiFacture = $envoiFacture;

        return $this;
    }

    /**
     * Get envoiFacture
     *
     * @return string
     */
    public function getEnvoiFacture()
    {
        return $this->envoiFacture;
    }

    /**
     * Is classe
     *
     * @param string $className
     * @return boolean
     */
    public function isClass($className)
    {
        if($className == 'Membre')
            return true;
        else
            return false;
    }









}
