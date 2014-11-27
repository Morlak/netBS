<?php

namespace Interne\FactureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Facture
 *
 * @ORM\Table(name="facture_factures")
 * @ORM\Entity(repositoryClass="Interne\FactureBundle\Entity\FactureRepository")
 */
class Facture
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /*
     * =========== RELATIONS ===============
     */

    /**
     * @var ArryCollection
     *
     * @ORM\OneToMany(targetEntity="Interne\FactureBundle\Entity\Rappel", mappedBy="facture", cascade={"persist", "remove"})
     */
    private $rappels;


    /**
     * @var ArryCollection
     * @ORM\ManyToMany(targetEntity="Facture", mappedBy="$factureParents", cascade={"persist"})
     * @ORM\JoinTable(name="facture_parent_child",
     *      joinColumns={@ORM\JoinColumn(name="factureParent_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="factureChild_id", referencedColumnName="id")})
     *
     *
     *
     */
    private $factureChilds;

    /**
     * @var ArryCollection
     * @ORM\ManyToMany(targetEntity="Facture", inversedBy="$factureChilds")
     * @ORM\JoinTable(name="facture_parent_child",
     *      joinColumns={@ORM\JoinColumn(name="factureChild_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="factureParent_id", referencedColumnName="id")})
     *
     *
     */
    private $factureParents;

    /*
     * ========== VARIABLES =================
     */

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var text
     *
     * @ORM\Column(name="remarque", type="text")
     */
    private $remarque;

    /**
     * @var float
     *
     * @ORM\Column(name="montantEmis", type="float")
     */
    private $montantEmis;

    /**
     * @var float
     *
     * @ORM\Column(name="montantRecu", type="float")
     */
    private $montantRecu;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="date")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePayement", type="date")
     */
    private $datePayement;


    /*
     * ============= FONCTIONS ============
     */

    public function __construct()
    {
        $this->rappels = new ArrayCollection();
        $this->setDatePayement(new \DateTime('0000-00-00'));
        $this->setMontantRecu(0);
        $this->statut = 'ouverte';

        $this->factureChilds = new ArrayCollection();
        $this->factureParents = new ArrayCollection();

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
     * Set id
     *
     * @param integer $id
     * @return Facture
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Facture
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     * @return Facture
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string 
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * Set montantEmis
     *
     * @param float $montantEmis
     * @return Facture
     */
    public function setMontantEmis($montantEmis)
    {
        $this->montantEmis = $montantEmis;

        return $this;
    }

    /**
     * Get montantEmis
     *
     * @return float 
     */
    public function getMontantEmis()
    {
        return $this->montantEmis;
    }

    /**
     * Set montantRecu
     *
     * @param float $montantRecu
     * @return Facture
     */
    public function setMontantRecu($montantRecu)
    {
        $this->montantRecu = $montantRecu;

        return $this;
    }

    /**
     * Get montantRecu
     *
     * @return float 
     */
    public function getMontantRecu()
    {
        return $this->montantRecu;
    }

    /**
    * Set statut
    *
    * @param string $statut
    * @return Facture
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
     * Add rappel
     *
     * @param Rappel $rappel
     * @return Facture
     */
    public function addRappel($rappel)
    {
        $this->rappels[] = $rappel;
        $rappel->setFacture($this);

        return $this;
    }

    /**
     * Remove rappel
     *
     * @param Rappel $rappel
     * @return Facture
     */
    public function removeRappel($rappel)
    {
        $this->rappels->remove($rappel);
        $rappel->setFacture(null);



        return $this;
    }

    /**
     * Set rappels
     *
     * @param ArrayCollection $rappels
     * @return Facture
     */
    public function setRappels(ArrayCollection $rappels)
    {
        $this->rappels = $rappels;

        foreach($rappels as $rappel)
        {
            $rappel->setFacture($this);
        }

        return $this;
    }

    /**
     * Get rappels
     *
     * @return ArrayCollection
     */
    public function getRappels()
    {
        return $this->rappels;
    }


    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Facture
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Get montantTotal
     *
     * @return float
     */
    public function getMontantTotal()
    {

        return $this->montantEmis + $this->getFraisRappel();
    }

    /**
     * Get fraisRappel
     *
     * @return float
     */
    public function getFraisRappel()
    {
        $fraisRappel = 0;
        foreach($this->rappels as $rappel)
        {
            $fraisRappel = $fraisRappel + $rappel->getFrais();
        }
        return $fraisRappel;
    }

    /**
     * Set datePayement
     *
     * @param \DateTime $datePayement
     * @return Facture
     */
    public function setDatePayement($datePayement)
    {
        $this->datePayement = $datePayement;

        return $this;
    }

    /**
     * Get datePayement
     *
     * @return \DateTime
     */
    public function getDatePayement()
    {
        return $this->datePayement;
    }

    /**
     * Get nombreRappels
     *
     * @return integer
     */
    public function getNombreRappels()
    {
        return $this->rappels->count();
    }



    /**
     * Get factureChilds
     *
     * @return ArrayCollection
     */
    public function getFactureChilds()
    {
        return $this->factureChilds;
    }

    /**
     * Add factureChild
     *
     * @param Facture factureChild
     * @return Facture
     */
    public function addFactureChild($factureChild)
    {
        $this->factureChilds[] = $factureChild;
        $factureChild->addFactureParent($this);

        return $this;
    }

    /**
     * Get factureParents
     *
     * @return ArrayCollection
     */
    public function getFactureParents()
    {
        return $this->factureParents;
    }

    /**
     * Add factureParent
     *
     * @param Facture factureParent
     * @return Facture
     */
    public function addFactureParent($factureParent)
    {
        $this->factureParents[] = $factureParent;

        return $this;
    }

    /**
     *
     * Get isParent
     *
     * @return Boolean
     */
    public function isParent()
    {
        if($this->factureChilds->count()>0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    /**
     * Get isChild
     *
     *
     * @return Boolean
     */
    public function isChild()
    {
        if($this->factureParents->count()>0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }





}
