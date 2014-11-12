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
     * @ORM\Column(name="entity", type="text")
     */
    private $entity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Interne\SecurityBundle\Entity\User")
     */
    private $user;

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
    private $classIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(name="className", type="string")
     */
    private $className;


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
     * Set entity
     *
     * @param string $entity
     * @return Validation
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string 
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Validation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
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
     * Set user
     *
     * @param \Interne\SecurityBundle\Entity\User $user
     * @return Validation
     */
    public function setUser(\Interne\SecurityBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Interne\SecurityBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set classIdentifier
     *
     * @param string $classIdentifier
     * @return Validation
     */
    public function setClassIdentifier($classIdentifier)
    {
        $this->classIdentifier = $classIdentifier;

        return $this;
    }

    /**
     * Get classIdentifier
     *
     * @return string 
     */
    public function getClassIdentifier()
    {
        return $this->classIdentifier;
    }

    /**
     * Set className
     *
     * @param string $className
     * @return Validation
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className
     *
     * @return string 
     */
    public function getClassName()
    {
        return $this->className;
    }
}
