<?php

namespace Interne\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fonction
 *
 * @ORM\Table(name="structure_fonctions")
 * @ORM\Entity(repositoryClass="Interne\StructureBundle\Entity\FonctionRepository")
 */
class Fonction
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
     * @var string
     *
     * @ORM\Column(name="abreviation", type="string", length=255)
     */
    private $abreviation;

    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Interne\StructureBundle\Entity\Attribution", mappedBy="fonction")
     */
    private $attributions;


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
     * @return Fonction
     */
    public function setNom($nom)
    {
        $this->nom = ucfirst($nom);

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return ucfirst($this->nom);
    }

    /**
     * Set abreviation
     *
     * @param string $abreviation
     * @return Fonction
     */
    public function setAbreviation($abreviation)
    {
        $this->abreviation = $abreviation;

        return $this;
    }

    /**
     * Get abreviation
     *
     * @return string 
     */
    public function getAbreviation()
    {
        return $this->abreviation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attribution = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add attribution
     *
     * @param \Interne\StructureBundle\Entity\Attribution $attribution
     * @return Fonction
     */
    public function addAttribution(\Interne\StructureBundle\Entity\Attribution $attribution)
    {
        $this->attributions[] = $attribution;
		$attributions->setFonction($this);
        return $this;
    }

    /**
     * Remove attribution
     *
     * @param \Interne\StructureBundle\Entity\Attribution $attribution
     */
    public function removeAttribution(\Interne\StructureBundle\Entity\Attribution $attribution)
    {
        $this->attributions->removeElement($attribution);
    }

    /**
     * Get attribution
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributions()
    {
        return $this->attributions;
    }
}
