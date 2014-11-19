<?php

namespace Interne\FichierBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Interne\FichierBundle\Entity\Adresse;

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
     * @var array
     *
     * @ORM\Column(name="telephones", type="array")
     */
    private $telephones;

    /**
     * @var array
     *
     * @ORM\Column(name="emails", type="array")
     */
    private $emails;



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
        if($this->adresse == null) return new Adresse();
        else return $this->adresse;
    }


    /**
     * @param $telephones
     * @return $this
     */
    public function setTelephones($telephones)
    {
        $this->telephones = $telephones;

        return $this;
    }

    /**
     * @param $telephone
     * @return $this
     */
    public function addTelephone($telephone) {
        $this->telephones = array($this->telephones, $telephone);

        return $this;
    }

    /**
     * @return array
     */
    public function getTelephones()
    {
        return ( $this->telephones == null ) ? array() : $this->telephones;
    }

    /**
     * @param $emails
     * @return $this
     */
    public function setEmails($emails)
    {
        $this->emails = $emails;

        return $this;
    }

    /**
     * @param $email
     * @return $this
     */
    public function addEmail($email) {
        $this->emails = array($this->emails, $email);

        return $this;
    }

    /**
     * @return array
     */
    public function getEmails()
    {
        return ( $this->emails == null ) ? array() : $this->emails;
    }

}
