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
     * @var \stdClass
     *
     * @ORM\Column(name="distinction", type="object")
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
        $this->obtention = new \Datetime($obtention);
    
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
     * Set distinction
     *
     * @param \stdClass $distinction
     * @return ObtentionDistinction
     */
    public function setDistinction($distinction)
    {
        $this->distinction = $distinction;
    
        return $this;
    }

    /**
     * Get distinction
     *
     * @return \stdClass 
     */
    public function getDistinction()
    {
        return $this->distinction;
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
}
