<?php

namespace Externe\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Album
 *
 * @ORM\Table(name="galerie_albums")
 * @ORM\Entity(repositoryClass="Externe\GalerieBundle\Entity\AlbumRepository")
 */
class Album
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
     * @var Array
     *
     * @ORM\Column(name="photos", type="array")
     */
    private $photos;
    
    /**
     * @var Dossier
     * @ORM\ManyToOne(targetEntity="Externe\GalerieBundle\Entity\Dossier", inversedBy="albums")
     */
    private $dossier;
    
    /**
     * @var Droit
     * @ORM\ManyToOne(targetEntity="Externe\GalerieBundle\Entity\Droit", inversedBy="albums")
     */
    private $droit;

    public function __construct() {
        
        $this->photos = array();
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
     * @return Album
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
     * @return Album
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
     * Set photos
     *
     * @param array $photos
     * @return Album
     */
    public function setPhotos($photos)
    {
        //On merge les arrays pour ajouter les photos
        $new = array_merge($this->photos, $photos);
        $this->photos = $new;
        
        return $this;
    }

    /**
     * Get photos
     *
     * @return array 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set dossier
     *
     * @param \Externe\GalerieBundle\Entity\Dossier $dossier
     * @return Album
     */
    public function setDossier(\Externe\GalerieBundle\Entity\Dossier $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \Externe\GalerieBundle\Entity\Dossier 
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set droit
     *
     * @param \Externe\GalerieBundle\Entity\Droit $droit
     * @return Album
     */
    public function setDroit(\Externe\GalerieBundle\Entity\Droit $droit = null)
    {
        $this->droit = $droit;
        $droit->addAlbum($this);
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
