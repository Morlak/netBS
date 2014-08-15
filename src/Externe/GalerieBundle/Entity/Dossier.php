<?php

namespace Externe\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dossier
 *
 * @ORM\Table(name="galerie_dossiers")
 * @ORM\Entity(repositoryClass="Externe\GalerieBundle\Entity\DossierRepository")
 */
class Dossier
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime")
     */
    private $creation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="access", type="boolean")
     */
    private $access;

    /**
     * @var Dossier
     * @ORM\ManyToOne(targetEntity="Externe\GalerieBundle\Entity\Dossier", inversedBy="enfants")
     */
    private $parent;

    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Externe\GalerieBundle\Entity\Dossier", mappedBy="parent")
     */
    private $enfants;
    
    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Externe\GalerieBundle\Entity\Album", mappedBy="dossier")
     */
    private $albums;
    
    /**
     * @var Droit
     * @ORM\ManyToOne(targetEntity="Externe\GalerieBundle\Entity\Droit", inversedBy="dossiers", cascade={"persist"})
     */
    private $droit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enfants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Dossier
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
     * Set creation
     *
     * @param \DateTime $creation
     * @return Dossier
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime 
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set parent
     *
     * @param \Externe\GalerieBundle\Entity\Dossier $parent
     * @return Dossier
     */
    public function setParent($parent = null)
    {        
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Externe\GalerieBundle\Entity\Dossier 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add enfants
     *
     * @param \Externe\GalerieBundle\Entity\Dossier $enfants
     * @return Dossier
     */
    public function addEnfant(\Externe\GalerieBundle\Entity\Dossier $enfants)
    {
        $this->enfants[] = $enfants;

        return $this;
    }

    /**
     * Remove enfants
     *
     * @param \Externe\GalerieBundle\Entity\Dossier $enfants
     */
    public function removeEnfant(\Externe\GalerieBundle\Entity\Dossier $enfants)
    {
        $this->enfants->removeElement($enfants);
    }

    /**
     * Get enfants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnfants()
    {
        return $this->enfants;
    }

    /**
     * Add albums
     *
     * @param \Externe\GalerieBundle\Entity\Album $albums
     * @return Dossier
     */
    public function addAlbum(\Externe\GalerieBundle\Entity\Album $albums)
    {
        $this->albums[] = $albums;

        return $this;
    }

    /**
     * Remove albums
     *
     * @param \Externe\GalerieBundle\Entity\Album $albums
     */
    public function removeAlbum(\Externe\GalerieBundle\Entity\Album $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Set access
     *
     * @param boolean $access
     * @return Dossier
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return boolean 
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set droit
     *
     * @param \Externe\GalerieBundle\Entity\Droit $droit
     * @return Dossier
     */
    public function setDroit(\Externe\GalerieBundle\Entity\Droit $droit = null)
    {
        $this->droit = $droit;
        $droit->addDossier($this);
        return $this;
    }

    /**
     * Get droit
     *
     * @return \Externe\GalerieBundle\Entity\Droit 
     */
    public function getDroit()
    {
        return $this->droit;
    }
}
