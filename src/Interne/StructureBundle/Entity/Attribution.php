<?php

namespace Interne\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attribution
 *
 * @ORM\Table(name="structure_attributions")
 * @ORM\Entity(repositoryClass="Interne\StructureBundle\Entity\AttributionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Attribution
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
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=true)
     */
    private $dateFin;
    
    /**
     * @var Groupe $groupe
     * 
     * @ORM\ManyToOne(targetEntity="Groupe", inversedBy="attributions")
     * @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
     */
     private $groupe;
     
     /**
     * @var Interne\FichierBundle\Entity\Membre $membre
     * 
     * @ORM\ManyToOne(targetEntity="Interne\FichierBundle\Entity\Membre", inversedBy="attributions")
     * @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
     */
     private $membre;
     
      /**
     * @var Fonction $fonction
     * 
     * @ORM\ManyToOne(targetEntity="Interne\StructureBundle\Entity\Fonction", inversedBy="attributions")
     * @ORM\JoinColumn(name="fonction_id", referencedColumnName="id")
     */
     private $fonction;
    
    


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
     * Set dateDebut
     *
     * @param string $dateDebut
     * @return Attribution
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param string $dateFin
     * @return Attribution
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    
        return $this;
    }
    

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set groupe
     *
     * @param \Interne\StructureBundle\Entity\Groupe $groupe
     * @return Attribution
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
     * Set membre
     *
     * @param \Interne\FichierBundle\Entity\Membre $membre
     * @return Attribution
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
     * Set fonction
     *
     * @param \Interne\StructureBundle\Entity\Fonction $fonction
     * @return Attribution
     */
    public function setFonction(\Interne\StructureBundle\Entity\Fonction $fonction = null)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return \Interne\StructureBundle\Entity\Fonction 
     */
    public function getFonction()
    {
        return $this->fonction;
    }
}
