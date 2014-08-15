<?php

namespace Interne\StammBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Download
 *
 * @ORM\Table(name="stamm_downloads")
 * @ORM\Entity(repositoryClass="Interne\StammBundle\Entity\DownloadRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Download
{
	public function __construct() {
		
		$this->date = new \Datetime();
	}
	
	private $temp;
	
	private $absPath;
	
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
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;


	/**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
    /**
     * @var int
     * 
       @ORM\Column(name="size", type="integer", length=100)
     */
    private $size;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    
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
     * @return Download
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
     * Set path
     *
     * @param string $path
     * @return Download
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Download
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Download
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
     * Set categorie
     *
     * @param string $categorie
     * @return Download
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    
    /**
     * Sets type
     * @param string $type
     * @return Download
     */
    public function setType($type) {
    	
    	$this->type = $type;
    	return $this;
    }
    
    /**
     * Get type
     * 
     * @return string
     */
    public function getType() {
    	
    	return $this->type;
    }
    
    /**
     * Sets size
     * @param int $size
     * @return Download
     */
    public function setSize($size) {
    	
    	$this->size = $size;
    }
    
    /**
     * Get size
     * @return int
     */
    public function getSize() {
    	
    	return $this->size;
    }
    
    public function getAbsPath() {
    	
    	return $this->absPath;
    }
    
    public function setAbsPath($absPath) {
    	
    	$this->absPath = $absPath;
    	return $this;
    }
    /**
     * Sets file
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check si on a une vieille image d'un path
        if (isset($this->path)) {
            
            // on stocke l'ancien nom
            $this->temp = $this->path;
            $this->path = null;
            
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    
    /*
     * fonctions utiles concernant l'upload, recuperation
     * des fichiers
     */
    
    //Retourne le chemin absolu du fichier
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

	//Retourne le chemin relatif du fichier
    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

	//Retourne le chemin absolu du repertoire d'upload
    protected function getUploadRootDir()
    {
        // Le repertoire absolu dans lequel les fichiers
        // devraient êtres sauvés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

	//Retourne le nom du reprtoire d'upload
    protected function getUploadDir()
    {
        return 'uploads/downloads';
    }
    
    
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            
            //On génère un nom unique
            $filename   = md5(mt_rand(5, 10));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            
            //On stocke la taille et le mimetype
            $this->type = $this->getFile()->getMimeType();
            $this->size = $this->getFile()->getClientSize();
        }
    }
    
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
		
        // si erreur lors du deplacement du fichier, une erreur est automatiquement
        // balancée, permettant d'éviter que doctrine persiste l'entité
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // Vieux nom
        if (isset($this->temp)) {
        	
            unlink($this->getUploadRootDir().'/'.$this->temp);
            
            // On nettoie le chemin temporaire
            $this->temp = null;
            
        }
        $this->file = null;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
}
