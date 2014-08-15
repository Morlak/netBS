<?php

namespace Interne\FichierBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** 
 * @ORM\MappedSuperclass 
 */
abstract class Personne
{

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = "2")
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255)
     */
    protected $sexe;
    
    /**
     * @ORM\OneToOne(targetEntity="Interne\FichierBundle\Entity\Adresse", cascade={"persist", "remove"})
     */
    protected $adresse;


    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return Personne
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    
        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
    }
    
    /**
     * Set adresse
     *
     * @param \Interne\FichierBundle\Entity\Adresse $adresse
     * @return Famille
     */
    public function setAdresse(\Interne\FichierBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return \Interne\FichierBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}
