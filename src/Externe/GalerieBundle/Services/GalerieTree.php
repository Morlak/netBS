<?php

namespace Externe\GalerieBundle\Services;

class GalerieTree
{
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * retourne la hierarchie bioen construite pour jstree supply en
     * json data
     */
    public function getJsonTree($droit) {
        
        $dossierRepo    = $this->em->getRepository('ExterneGalerieBundle:Dossier');
        $albumRepo      = $this->em->getRepository('ExterneGalerieBundle:Album');
        
        $dossiers = $dossierRepo->findByDroit($droit);
        $tree     = array();
        
        //On récupère les albums du groupe racine
        $albums = $albumRepo->findAlbumsForThisDroitAndDossier($droit, null);
        $ac     = 0;
        
        foreach($dossiers as $d) {
            
            if($d->getParent() === null) {
                
                $tree[$ac]['li_attr']['dossier_id'] = $d->getId();
                $tree[$ac]['li_attr']['type'] = 'dossier';
                $tree[$ac]['text'] = $d->getNom();
                $tree[$ac]['children'] = $this->getJsonEnfantsRecursive($droit, $d);
                
                $ac++;
            }
        }
        
        foreach($albums as $a) {
    
            $tree[$ac]['text'] = $a->getNom();
            $tree[$ac]['li_attr']['creation'] = $a->getCreation();
            $tree[$ac]['li_attr']['album_id'] = $a->getId();
            $tree[$ac]['li_attr']['type'] = 'album';
            $tree[$ac]['icon'] = '/netBS/web/static/images/galerie/album.png';
            $ac++;
        }
        
        return $tree;
       
    }
    
    private function getJsonEnfantsRecursive($droit, $dossier) {
        
        $albumRepo  = $this->em->getRepository('ExterneGalerieBundle:Album');
        $d          = array();
        
        $albums = $albumRepo->findByDossier($dossier->getId());
        $ac     = 0;
        
        foreach($dossier->getEnfants() as $enfant) {
            
            $d[$ac]['children'] = $this->getJsonEnfantsRecursive($droit, $enfant);
            $d[$ac]['li_attr']['dossier_id'] = $enfant->getId();
            $d[$ac]['li_attr']['type'] = 'dossier';
            $d[$ac]['text'] = $enfant->getNom();
            $ac++;
        }
        
        foreach($albums as $a) {
    
            $d[$ac]['text'] = $a->getNom();
            $d[$ac]['icon'] = '/netBS/web/static/images/galerie/album.png';
            $d[$ac]['li_attr']['creation'] = $a->getCreation();
            $d[$ac]['li_attr']['type'] = 'album';
            $d[$ac]['li_attr']['album_id'] = $a->getId();
            $ac++;
        }
        
        return $d;
    }
}