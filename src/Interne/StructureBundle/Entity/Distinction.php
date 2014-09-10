<?php

namespace Interne\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Distinction
 *
 * @ORM\Table(name="structure_distinctions")
 * @ORM\Entity(repositoryClass="Interne\StructureBundle\Entity\DistinctionRepository")
 */
class Distinction
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
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Interne\StructureBundle\Entity\ObtentionDistinction", mappedBy="distinction")
     */
    private $obtentionDistinctions;
    
    /**
     * @var text
     * 
     * @ORM\Column(name="remarques", type="text")
     */
    private $remarques;


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
     * @return Distinction
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
     * Set remarques
     *
     * @param string $remarques
     * @return Distinction
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
     * Constructor
     */
    public function __construct()
    {
        $this->obtentionDistinctions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add obtentionDistinctions
     *
     * @param \Interne\StructureBundle\Entity\ObtentionDistinction $obtentionDistinctions
     * @return Distinction
     */
    public function addObtentionDistinction(\Interne\StructureBundle\Entity\ObtentionDistinction $obtentionDistinctions)
    {
        $this->obtentionDistinctions[] = $obtentionDistinctions;

        return $this;
    }

    /**
     * Remove obtentionDistinctions
     *
     * @param \Interne\StructureBundle\Entity\ObtentionDistinction $obtentionDistinctions
     */
    public function removeObtentionDistinction(\Interne\StructureBundle\Entity\ObtentionDistinction $obtentionDistinctions)
    {
        $this->obtentionDistinctions->removeElement($obtentionDistinctions);
    }

    /**
     * Get obtentionDistinctions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObtentionDistinctions()
    {
        return $this->obtentionDistinctions;
    }
}
