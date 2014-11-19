<?php

namespace Interne\GlobalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValidatorCreation
 *
 * @ORM\Table(name="global_validation")
 * @ORM\Entity(repositoryClass="Interne\GlobalBundle\Entity\ValidationRepository")
 */
class Validation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string")
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="classIdentifier", type="string")
     */
    private $identifier;

    /**
     * @var integer
     *
     * @ORM\Column(name="entityId", type="integer")
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="repo", type="string")
     */
    private $repo;

    /**
     * @var string
     *
     * @ORM\Column(name="entityName", type="string")
     */
    private $entityName;

    /**
     * @ORM\OneToMany(targetEntity="Interne\GlobalBundle\Entity\Modification", mappedBy="validation", cascade={"persist", "remove"})
     */
    private $modifications;

    /**
     * @var string
     *
     * @ORM\Column(name="fullClass", type="string")
     */
    private $fullClass;


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
     * Constructor
     */
    public function __construct()
    {
        $this->modifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Validation
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
     * Set identifier
     *
     * @param string $identifier
     * @return Validation
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set repo
     *
     * @param string $repo
     * @return Validation
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;

        return $this;
    }

    /**
     * Get repo
     *
     * @return string 
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * Add modifications
     *
     * @param \Interne\GlobalBundle\Entity\Modification $modification
     * @return Validation
     */
    public function addModification(\Interne\GlobalBundle\Entity\Modification $modification)
    {
        $this->modifications[] = $modification;
        $modification->setValidation($this);

        return $this;
    }

    /**
     * Remove modifications
     *
     * @param \Interne\GlobalBundle\Entity\Modification $modifications
     */
    public function removeModification(\Interne\GlobalBundle\Entity\Modification $modifications)
    {
        $this->modifications->removeElement($modifications);
    }

    /**
     * Get modifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModifications()
    {
        return $this->modifications;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     * @return Validation
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityName
     *
     * @param string $entityName
     * @return Validation
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName
     *
     * @return string 
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set fullClass
     *
     * @param string $fullClass
     * @return Validation
     */
    public function setFullClass($fullClass)
    {
        $this->fullClass = $fullClass;

        return $this;
    }

    /**
     * Get fullClass
     *
     * @return string 
     */
    public function getFullClass()
    {
        return $this->fullClass;
    }
}
