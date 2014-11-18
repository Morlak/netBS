<?php

namespace Interne\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Interne\StructureBundle\Entity\Type;

/**
 * Groupe
 *
 * @ORM\Table(name="structure_groupes")
 * @ORM\Entity(repositoryClass="Interne\StructureBundle\Entity\GroupeRepository")
 */
class Groupe
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
     * @var Groupe
     * @ORM\ManyToOne(targetEntity="Interne\StructureBundle\Entity\Groupe", inversedBy="enfants")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Interne\StructureBundle\Entity\Groupe", mappedBy="parent")
     */
    private $enfants;

	/**      
	 * @var ArrayCollection 
     * 
     * @ORM\OneToMany(targetEntity="Attribution", mappedBy="groupe", cascade={"persist"})
     */
    private $attributions;

    /**
     * @var Type $type
     * 
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="groupes", cascade={"persist"})
     */
    private $type;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enfants = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Groupe
     */
    public function setNom($nom)
    {
        $this->nom = ucwords($nom);
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return ucwords($this->nom);
    }

    /**
     * Set parent
     *
     * @param \stdClass $parent
     * @return Groupe
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \stdClass 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set enfants
     *
     * @param array $enfants
     * @return Groupe
     */
    public function setEnfants($enfants)
    {
        $this->enfants = $enfants;
    
        return $this;
    }

    /**
     * Get enfants
     *
     * @return array 
     */
    public function getEnfants()
    {
        return $this->enfants;
    }
    
    /**
     * Add enfants
     *
     * @param \Interne\StructureBundle\Entity\Groupe $enfants
     * @return Groupe
     */
    public function addEnfant(\Interne\StructureBundle\Entity\Groupe $enfants)
    {
        $this->enfants[] = $enfants;
    
        return $this;
    }

    /**
     * Remove enfants
     *
     * @param \Interne\StructureBundle\Entity\Groupe $enfants
     */
    public function removeEnfant(\Interne\StructureBundle\Entity\Groupe $enfants)
    {
        $this->enfants->removeElement($enfants);
    }

    /**
     * Add attributions
     *
     * @param \Interne\StructureBundle\Entity\Attribution $attributions
     * @return Groupe
     */
    public function addAttribution(\Interne\StructureBundle\Entity\Attribution $attributions)
    {
        $this->attributions[] = $attributions;
    
        return $this;
    }

    /**
     * Remove attributions
     *
     * @param \Interne\StructureBundle\Entity\Attribution $attributions
     */
    public function removeAttribution(\Interne\StructureBundle\Entity\Attribution $attributions)
    {
        $this->attributions->removeElement($attributions);
    }

    /**
     * Get attributions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributions()
    {
        return $this->attributions;
    }

    /**
     * Set type
     *
     * @param \Interne\StructureBundle\Entity\Type $type
     * @return Groupe
     */
    public function setType(\Interne\StructureBundle\Entity\Type $type = null)
    {
        $this->type = $type;
	    $type->addGroupe($this);
        return $this;
    }

    /**
     * Get type
     *
     * @return \Interne\StructureBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }

    public function getMembers()
    {

        $members = array();
        $today   = new \Datetime();

        foreach ($this->getAttributions() as $attribution) {
            if ($attribution->getDateFin() == null || $attribution->getDateFin() > $today)
                array_push($members, $attribution->getMembre());

        }

        return $members;

    }

    public function getMembersRecursive()
    {

        $members = $this->getMembers();

        foreach ($this->getEnfants() as $childGroup) {
            $members = array_merge($members, $childGroup->getMembersRecursive());
        }

        return $members;
    }
}
