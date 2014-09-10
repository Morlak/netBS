<?php

namespace Interne\FichierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Membre
 *
 * @ORM\Table(name="fichier_membres")
 * @ORM\Entity(repositoryClass="Interne\FichierBundle\Entity\MembreRepository")
 */
class Membre extends Personne
{

    public function __construct()
    {

        $this->inscription = new \Datetime();

        $this->telephones = array("");
        $this->emails = array("");
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Interne\FichierBundle\Entity\Famille", inversedBy="membres")
     * @ORM\JoinColumn(name="famille_id", referencedColumnName="id")
     */
    private $famille;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Interne\StructureBundle\Entity\Attribution", mappedBy="membre", cascade={"persist", "remove"})
     */
    private $attributions;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Interne\StructureBundle\Entity\ObtentionDistinction", mappedBy="membre", cascade={"persist", "remove"})
     */
    private $distinctions;

    /**
     * @var date
     *
     * @ORM\Column(name="naissance", type="date")
     */
    private $naissance;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_bs", type="integer", nullable=true)
     */
    private $numeroBs;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_avs", type="string", length=255, nullable=true)
     */
    private $numeroAvs;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=true)
     */
    private $statut;

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
     * @var date
     *
     * @ORM\Column(name="inscription", type="date")
     */
    private $inscription;

    /**
     * @var text
     *
     * @ORM\Column(name="remarques", type="text", nullable=true)
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
     * Set famille
     *
     * @param Famille $famille
     * @return Membre
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return Famille
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set nom
     *
     * @param Famille $famille
     * @return Membre
     */
    public function setNom($famille)
    {
        $this->setFamille($famille);

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {

        if ($this->getFamille() != null)
            return $this->getFamille()->getNom();
        else
            return "Pas dans une famille...";
    }


    /**
     * Set distinctions
     *
     * @param array $distinctions
     * @return Membre
     */
    public function setDistinctions($distinctions)
    {
        $this->distinctions = $distinctions;

        return $this;
    }

    /**
     * Get distinctions
     *
     * @return array
     */
    public function getDistinctions()
    {
        return $this->distinctions;
    }

    /**
     * Set numeroBs
     *
     * @param integer $numeroBs
     * @return Membre
     */
    public function setNumeroBs($numeroBs)
    {
        $this->numeroBs = $numeroBs;

        return $this;
    }

    /**
     * Get numeroBs
     *
     * @return integer
     */
    public function getNumeroBs()
    {
        return $this->numeroBs;
    }

    /**
     * Set numeroAvs
     *
     * @param string $numeroAvs
     * @return Membre
     */
    public function setNumeroAvs($numeroAvs)
    {
        $this->numeroAvs = $numeroAvs;

        return $this;
    }

    /**
     * Get numeroAvs
     *
     * @return string
     */
    public function getNumeroAvs()
    {
        return $this->numeroAvs;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Membre
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
        return $this->telephones;
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
        return $this->emails;
    }


    /**
     * Set naissance
     *
     * @param \DateTime $naissance
     * @return Membre
     */
    public function setNaissance($naissance)
    {
        $this->naissance = new \Datetime($naissance);

        return $this;
    }

    /**
     * Get naissance
     *
     * @return \DateTime
     */
    public function getNaissance()
    {
        return $this->naissance;
    }

    /**
     * Set inscription
     *
     * @param \DateTime $inscription
     * @return Membre
     */
    public function setInscription($inscription)
    {
        $this->inscription = $inscription;

        return $this;
    }

    /**
     * Get inscription
     *
     * @return \DateTime
     */
    public function getInscription()
    {
        return $this->inscription;
    }


    /**
     * Add attributions
     *
     * @param \Interne\StructureBundle\Entity\Attribution $attributions
     * @return Membre
     */
    public function addAttribution(\Interne\StructureBundle\Entity\Attribution $attributions)
    {
        $this->attributions[] = $attributions;
        $attributions->setMembre($this);
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
     * Set adresse
     *
     * @param \Interne\FichierBundle\Entity\Adresse $adresse
     * @return Membre
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


    /**
     * Set remarques
     *
     * @param string $remarques
     * @return Adresse
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
     * Add distinctions
     *
     * @param \Interne\StructureBundle\Entity\ObtentionDistinction $distinctions
     * @return Membre
     */
    public function addDistinction(\Interne\StructureBundle\Entity\ObtentionDistinction $distinctions)
    {
        $this->distinctions[] = $distinctions;
        $distinctions->setMembre($this);
        return $this;
    }

    /**
     * Remove distinctions
     *
     * @param \Interne\StructureBundle\Entity\ObtentionDistinction $distinctions
     */
    public function removeDistinction(\Interne\StructureBundle\Entity\ObtentionDistinction $distinctions)
    {
        $this->distinctions->removeElement($distinctions);
    }
}
