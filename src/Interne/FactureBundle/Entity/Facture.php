<?php

namespace Interne\FactureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Interne\FichierBundle\Entity\Membre;
use Interne\FichierBundle\Entity\Famille;

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
     * @ORM\OneToMany(targetEntity="Interne\FactureBundle\Entity\Rappel",
     *                mappedBy="facture", cascade={"persist", "remove"})
     */
    private $rappels;

    /**
     * @var ArryCollection
     *
     * @ORM\OneToMany(targetEntity="Interne\FactureBundle\Entity\Creance",
     *                mappedBy="facture", cascade={"persist", "remove"})
     */
    private $creances;

    /**
     * @var Membre
     *
     * @ORM\ManyToOne(targetEntity="Interne\FichierBundle\Entity\Membre", inversedBy="factures")
     * @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
     */
    private $membre;

    /**
     * @var Famille
     *
     * @ORM\ManyToOne(targetEntity="Interne\FichierBundle\Entity\Famille", inversedBy="factures")
     * @ORM\JoinColumn(name="famille_id", referencedColumnName="id")
     */
    private $famille;

    /*
     * ========== VARIABLES =================
     */


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

        $this->creances = new ArrayCollection();


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

        return $this->getMontantCreances() + $this->getFraisRappel();
    }

    /**
     * Get montantCreances
     *
     * @return float
     */
    public function getMontantCreances()
    {
        $montantCreances = 0;
        foreach($this->creances as $creance)
        {
            $montantCreances = $montantCreances + $creance->getMontantEmis();
        }
        return $montantCreances;
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

/*

    **
     * Get factureChilds
     *
     * @return ArrayCollection
     *
    public function getFactureChilds()
    {
        return $this->factureChilds;
    }

    **
     * Add factureChild
     *
     * @param Facture factureChild
     * @return Facture
     *
    public function addFactureChild($factureChild)
    {
        $this->factureChilds[] = $factureChild;
        $factureChild->addFactureParent($this);

        return $this;
    }

    **
     * Get factureParents
     *
     * @return ArrayCollection
     *
    public function getFactureParents()
    {
        return $this->factureParents;
    }

    **
     * Add factureParent
     *
     * @param Facture factureParent
     * @return Facture
     *
    public function addFactureParent($factureParent)
    {
        $this->factureParents[] = $factureParent;

        return $this;
    }

    **
     *
     * Get isParent
     *
     * @return Boolean
     *
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

    **
     * Get isChild
     *
     *
     * @return Boolean
     *
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

    **
     * Set asPayed
     *
     *
     *
    public function setAsPayed(float $montantRecu,\DateTime $datePayement = null)
    {

        if($datePayement == null)
        {
            $datePayement = new \DateTime();
            $date = new \DateTime();
        }
        $date->createFromFormat('d/m/Y',$datePayement);
        $this->setDatePayement($date);

        $this->setMontantRecu($montantRecu);
        $this->setStatut('payee');

        if($this->isParent())
        {
            *
             * On réparti la somme payée dans tout les factures
             * du groupe en répartisant aussi la diffreance
             * si il y en a une.
             *

        }

        *
        finir ici!!!!
        *


    }
*/

    /*
     * Set membre
     *
     * @param Membre $membre
     * @return Rappel
     *
    public function setMembre($membre)
    {
        $this->membre = $membre;

        return $this;
    }

    /*
     * Get membre
     *
     * @return Membre
     *
    public function getMembre()
    {
        return $this->membre;
    }
    */

    /**
     * Add creance
     *
     * @param Creance $creance
     * @return Facture
     */
    public function addCreance($creance)
    {
        $this->creances[] = $creance;
        $creance->setFacture($this);

        return $this;
    }

    /**
     * Remove creance
     *
     * @param Creance $creance
     * @return Facture
     */
    public function removeCreance($creance)
    {
        $this->creances->remove($creance);
        $creance->setFacture(null);

        return $this;
    }

    /**
     * Set creances
     *
     * @param ArrayCollection $creances
     * @return Facture
     */
    public function setCreances(ArrayCollection $creances)
    {
        $this->creances = $creances;

        foreach($creances as $creance)
        {
            $creance->setFacture($this);
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
     * Set membre
     *
     * @param Membre $membre
     * @return Facture
     */
    public function setMembre($membre)
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Get membre
     *
     * @return Membre
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * Set famille
     *
     * @param Famille $famille
     * @return Facture
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
        return $this->famille;
    }






}
