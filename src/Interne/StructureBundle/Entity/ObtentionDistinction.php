<?php

namespace Interne\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObtentionDistinction
 *
 * @ORM\Table(name="structure_obtention_distinctions")
 * @ORM\Entity(repositoryClass="Interne\StructureBundle\Entity\ObtentionDistinctionRepository")
 */
class ObtentionDistinction
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
     * @var \DateTime
     *
     * @ORM\Column(name="obtention", type="date", nullable=false)
     */
    private $obtention;

    /**
     * @var Distinction $distinctions
     * 
     * @ORM\ManyToOne(targetEntity="Interne\StructureBundle\Entity\Distinction", inversedBy="obtentionDistinctions")
     */
    private $distinction;


    /**
     * @var membre
     * 
     * @ORM\ManyToOne(targetEntity="Interne\FichierBundle\Entity\Membre", inversedBy="distinctions")
     */
    private $membre;


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
     * Set obtention
     *
     * @param \DateTime $obtention
     * @return ObtentionDistinction
     */
    public function setObtention($obtention)
    {
        $this->obtention = $obtention;
    
        return $this;
    }

    /**
     * Get obtention
     *
     * @return \DateTime 
     */
    public function getObtention()
    {
        return $this->obtention;
    }

    /**
     * Set membre
     *
     * @param \Interne\FichierBundle\Entity\Membre $membre
     * @return ObtentionDistinction
     */
    public function setMembre(\Interne\FichierBundle\Entity\Membre $membre = null)
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Get membre
     *
     * @return \Interne\FichierBundle\Entity\Membre 
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * Set distinction
     *
     * @param \Interne\StructureBundle\Entity\Distinction $distinction
     * @return ObtentionDistinction
     */
    public function setDistinction(\Interne\StructureBundle\Entity\Distinction $distinction = null)
    {
        $this->distinction = $distinction;

        return $this;
    }

    /**
     * Get distinction
     *
     * @return \Interne\StructureBundle\Entity\Distinction 
     */
    public function getDistinction()
    {
        return $this->distinction;
    }
}
