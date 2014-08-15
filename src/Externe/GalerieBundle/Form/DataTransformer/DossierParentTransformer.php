<?php

namespace Externe\GalerieBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\TaskBundle\Entity\Issue;

class DossierParentTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;
    
    public function __construct(ObjectManager $om)  {
        
        $this->om = $om;
    }
    
    public function transform($dossier) {
        
        if(null === $dossier) return "";
        return $dossier->getId();
    }
    
    public function reverseTransform($number) {
        
        if(!$number) return null;
        
        if($number == 0) {
            
            return null;
        }
        
        $folder = $this->om
                ->getRepository('ExterneGalerieBundle:Dossier')
                ->find($number);
        
        return $folder;
    }
}