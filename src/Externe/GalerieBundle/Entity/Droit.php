<?php

namespace Externe\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Droit
 *
 * @ORM\Table(name="galerie_droits")
 * @ORM\Entity(repositoryClass="Externe\GalerieBundle\Entity\DroitRepository")
 */
class Droit
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
     * @orm\OneToOne(targetEntity="Interne\StructureBundle\Entity\Groupe", cascade={"persist"})
     */
    private $groupe;

    /**
     * @var boolean
     *
     * @ORM\Column(name="droit_album", type="boolean")
     */
    private $droitAlbum;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="albums_visibles", type="boolean")
     */
    private $albumsVisibles;
    
    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Externe\GalerieBundle\Entity\Dossier", mappedBy="droit", cascade={"persist"})
     */
    private $dossiers;
    
    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Externe\GalerieBundle\Entity\Album", mappedBy="droit")
     */
    private $albums;
    
    /**
     * @var string
     *
     * @ORM\Column(name="color1", type="string", length=100)
     */
    private $color1;
    
    /**
     * @var string
     *
     * @ORM\Column(name="color2", type="string", length=100)
     */
    private $color2;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dossiers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->albums   = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set droitAlbum
     *
     * @param boolean $droitAlbum
     * @return Droit
     */
    public function setDroitAlbum($droitAlbum)
    {
        $this->droitAlbum = $droitAlbum;

        return $this;
    }

    /**
     * Get droitAlbum
     *
     * @return boolean 
     */
    public function getDroitAlbum()
    {
        return $this->droitAlbum;
    }

    /**
     * Set groupe
     *
     * @param \Interne\StructureBundle\Entity\Groupe $groupe
     * @return Droit
     */
    public function setGroupe(\Interne\StructureBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \Interne\StructureBundle\Entity\Groupe 
     */
    public function getGroupe()
    {
        return $this->groupe;
    }


    /**
     * Set albumsVisibles
     *
     * @param boolean $albumsVisibles
     * @return Droit
     */
    public function setAlbumsVisibles($albumsVisibles)
    {
        $this->albumsVisibles = $albumsVisibles;

        return $this;
    }

    /**
     * Get albumsVisibles
     *
     * @return boolean 
     */
    public function getAlbumsVisibles()
    {
        return $this->albumsVisibles;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Droit
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add dossiers
     *
     * @param \Externe\GalerieBundle\Entity\Dossier $dossiers
     * @return Droit
     */
    public function addDossier(\Externe\GalerieBundle\Entity\Dossier $dossiers)
    {
        $this->dossiers[] = $dossiers;

        return $this;
    }

    /**
     * Remove dossiers
     *
     * @param \Externe\GalerieBundle\Entity\Dossier $dossiers
     */
    public function removeDossier(\Externe\GalerieBundle\Entity\Dossier $dossiers)
    {
        $this->dossiers->removeElement($dossiers);
    }

    /**
     * Get dossiers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDossiers()
    {
        return $this->dossiers;
    }

    /**
     * Set color1
     *
     * @param string $color1
     * @return Droit
     */
    public function setColor1($color1)
    {
        $this->color1 = $color1;

        return $this;
    }

    /**
     * Get color1
     *
     * @return string 
     */
    public function getColor1()
    {
        return $this->color1;
    }

    /**
     * Set color2
     *
     * @param string $color2
     * @return Droit
     */
    public function setColor2($color2)
    {
        $this->color2 = $color2;

        return $this;
    }

    /**
     * Get color2
     *
     * @return string 
     */
    public function getColor2()
    {
        return $this->color2;
    }

    /**
     * Add albums
     *
     * @param \Externe\GalerieBundle\Entity\Album $albums
     * @return Droit
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
}
