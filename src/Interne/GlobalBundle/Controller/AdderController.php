<?php

namespace Interne\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

use Interne\StructureBundle\Entity\Attribution;
use Interne\StructureBundle\Entity\ObtentionDistinction;


class AdderController extends Controller
{
    
    /**
     * la méthode getBasicAttributionsData va renvoyer en ajax les données de bases pour
     * adder attributions, comme la liste des fonctions, groupes, etc...
     */
    public function getBasicDataAttributionsAction() {
        
        //On récupère les ids
        $ids = $this->get('request')->request->get('ids');
        
        //On récupère toutes les fonctions disponibles
        $em     = $this->getDoctrine()->getManager();
        $fRepo  = $em->getRepository('InterneStructureBundle:Fonction');
        
        $fonctions  = $fRepo->findAll();
        $return     = array();
        $i          = 0;
        
        foreach($fonctions as $f) {
            
            $return['fonctions'][$i] = array(
                
                'id'    => $f->getId(),
                'nom'   => $f->getNom()
            );
            
            $i++;
        }
        
        //On récupère aussi les groupes disponibles
        $gRepo = $em->getRepository('InterneStructureBundle:Groupe');
        $groupes    = $gRepo->findAll();
        $j          = 0;
        
        foreach($groupes as $g) {
            
            $return['groupes'][$j] = array(
                
                'id'    => $g->getId(),
                'nom'   => $g->getNom()
            );
            
            $j++;
        }
        
        //On récupère les infos sur les membres concernés
        $mRepo = $em->getRepository('InterneFichierBundle:Membre');
        
        $attrs = array();
        
        for($k = 0; $k < count($ids); $k++) {
            
            $membre = $mRepo->find($ids[$k]);
            
            $return['membres'][$k] = array(
                
                'id'     => $ids[$k],
                'prenom' => $membre->getPrenom(),
                'nom'    => $membre->getFamille()->getNom()
            );
            
            /**
             * on récupère également les données nécessaires pour terminer des attributions
             * Pour chaque membre, on récupère les attributions courantes, et on compare leur
             * groupe, fonction et date de début, puis on renvoie les listes
             */
            $atx = $mRepo->findCurrentAttribution($ids[$k], false);
            $attrs = array_merge($attrs, $atx);
        }
        
        /**
         * après avoir récupéré la listes des attributions courantes de tous les membres, on les analyse
         */
        $aSpecs = array('fonctions' => array(), 'groupes' => array(), 'debuts' => array());
        
        foreach($attrs as $attribution) {
            
            //Analyse des fonctions
            if(count($aSpecs['fonctions']) == 0) {
                
                $aSpecs['fonctions'][0]['nom'] = $attribution->getFonction()->getNom();
                $aSpecs['fonctions'][0]['id'] = $attribution->getFonction()->getId();
            }
                
            else {
                
                $is = 1;
                
                for($i = 0; $i < count($aSpecs['fonctions']); $i++) {
                    
                    if($attribution->getFonction()->getId() != $aSpecs['fonctions'][$i]['id'])
                        $is++;
                }
                
                if($is != count($aSpecs['fonctions'])) {
                        
                    $n = count($aSpecs['fonctions']);
                    
                    $aSpecs['fonctions'][$n]['nom'] = $attribution->getFonction()->getNom();
                    $aSpecs['fonctions'][$n]['id']  = $attribution->getFonction()->getId();
                }
            }
            
            //Analyse des groupes
            if(count($aSpecs['groupes']) == 0) {
                
                $aSpecs['groupes'][0]['nom'] =$attribution->getGroupe()->getNom();
                $aSpecs['groupes'][0]['id'] =$attribution->getGroupe()->getId();
            }
                
            else {
                
                $is = 1;
                
                for($i = 0; $i < count($aSpecs['groupes']); $i++) {
                    
                    if($attribution->getGroupe()->getId() != $aSpecs['groupes'][$i]['id'])
                        $is++;
                }
                
                if($is != count($aSpecs['groupes'])) {
                    
                        $n = count($aSpecs['groupes']);
                        
                        $aSpecs['groupes'][$n]['nom'] = $attribution->getGroupe()->getNom();
                        $aSpecs['groupes'][$n]['id']  = $attribution->getGroupe()->getId();
                }
                
            }
            
            /**
             * pour analyser les dates de début, on analyse uniquement la date, pas l'heure
             * on formate donc l'objet datetime sans prendre compte de l'heure
             */
            if(count($aSpecs['debuts']) == 0)
                $aSpecs['debuts'][0] = $attribution->getDateDebut()->format('Y-m-d');
            
            else
            {
                $is = 1;
                
                for($i = 0; $i < count($aSpecs['debuts']); $i++) {
                    
                    if($attribution->getDateDebut()->format('Y-m-d') != $aSpecs['debuts'][$i])
                        $is++;
                }
                
                if($is != count($aSpecs['debuts'])) {
                    
                    $n = count($aSpecs['debuts']);
                    $aSpecs['debuts'][$n] = $attribution->getDateDebut()->format('Y-m-d');
                }
            }
        }
        
        $return['terminaison'] = $aSpecs;
        
        return new JsonResponse($return);
    }
    
    /**
     * la méthode addAttributions va permettre d'ajouter une masse de nouvelles
     * attributions. Elle récupère des données POST, puis exécute l'ajout de masse
     */
    public function addAttributionsAction() {
        
        //On récupère les infos
        $fonction   = $this->get('request')->request->get('fonction');
        $debut      = $this->get('request')->request->get('debut');
        $fin        = $this->get('request')->request->get('fin');
        $membres    = $this->get('request')->request->get('membres');
        
        //Pour chaque membre, on crée une attribution
        $em = $this->getDoctrine()->getManager();
        $fRepo = $em->getRepository('InterneStructureBundle:Fonction');
        $gRepo = $em->getRepository('InterneStructureBundle:Groupe');
        $mRepo = $em->getRepository('InterneFichierBundle:Membre');
        
        //On récupère la fonction globale
        $fonction   = $fRepo->find($fonction);
        
        for($i = 0; $i < count($membres); $i++) {
            
            $attribution = new Attribution();
            
            //On récupère le groupe lié
            $groupe = $gRepo->find($membres[$i]['groupe']);
            
            //On récupère le membre
            $membre = $mRepo->find($membres[$i]['id']);
            
            //On hydrate l'attribution
            $attribution->setGroupe($groupe);
            $attribution->setFonction($fonction);
            $attribution->setDateDebut(new \Datetime($debut));
            $attribution->setDateFin(($fin == null || $fin == '') ? null : new \Datetime($fin));
            $membre->addAttribution($attribution);
            
            $em->persist($attribution);
        }
        
        $em->flush();
        
        //Une fois toutes les attributions ajoutées, on renvoie une reponse juste
        return new JsonResponse(count($membres));
    }
    
    /**
     * la méthode endAttributions va permettre de balancer une date de fin à
     * un ensemble d'attributions pas finies
     */
    public function endAttributionsAction() {
        
        //On récupère les infos
        $parametre  = $this->get('request')->request->get('parametre');
        $donnee     = $this->get('request')->request->get('donnee');
        $membres    = $this->get('request')->request->get('ids');
        $fin        = $this->get('request')->request->get('fin');
        
        //Pour chaque membre, on crée une attribution
        $em = $this->getDoctrine()->getManager();
        $mRepo = $em->getRepository('InterneFichierBundle:Membre');
        
        $membres = explode(',', $membres);
        
        //On récupère les attributions courantes de chaque membre fourni
        $attrs = array();
        
        for($i = 0; $i < count($membres); $i++) {
            
            $atx = $mRepo->findCurrentAttribution($membres[$i], false);
            $attrs = array_merge($attrs, $atx);
        }
        
        //Après avoir récupéré toutes les attributions courantes, on prend toutes celles qui respectent
        //le critère de sélection de fin
        $finales = array();
        $c = 0;
        
        //Ensuite on scan par paramètre
        
        foreach($attrs as $attribution) {
            
            if($parametre == 'fonction') {
                
                if($attribution->getFonction()->getId() == $donnee) {
                    
                    $finales[$c] = $attribution;
                    $c++;
                }
            }
            
            else if($parametre == 'groupe') {
                
                if($attribution->getGroupe()->getId() == $donnee) {
                    
                    $finales[$c] = $attribution;
                    $c++;
                }
            }
            
            else if($parametre == 'debut') {
                
                //Pour la date, on récupère la date des attributions, on formate, et on compare
                if($attribution->getDateDebut()->format('Y-m-d') == $donnee) {
                    
                    $finales[$c] = $attribution;
                    $c++;
                }
            }
        }
        
        //Après avoir récupéré les attributions à influencer, on leur balance la date de fin fournie,
        //et si elle est vide, la date de maintenant
        $fin = ($fin == '' || $fin == null || $fin == ' ') ? new \Datetime('now') : new \Datetime($fin);
        
        foreach($finales as $atr) {
            
            $atr->setDateFin($fin);
            $em->persist($atr);
            $em->flush();
        }
        
        return new JsonResponse(1);
    }
    
    /**
     * Distinctions
     * ============
     *
     * getBasicData retourne les informations de bases pour l'âffichage de la modale
     * des distinctions
     */
    public function getBasicDataDistinctionsAction() {
        
        //Pour une distinction, on a besoin de l'ensemble des distinctions possibles
        //On récupère la liste
        $em = $this->getDoctrine()->getManager();
        $dRepo = $em->getRepository('InterneStructureBundle:Distinction');
        
        //On prend tout
        $distinctions = $dRepo->findAll();
        
        //Pour chaque distinction, on récupère le nom et l'Id
        $return = array();
        
        foreach($distinctions as $k => $d) {
            
            $return[$k]['nom'] = $d->getNom();
            $return[$k]['id'] = $d->getId();
        }
        
        //Et on renvoie
        return new JsonResponse($return);
    }
    
    /**
     * permet d'ajouter des distinctions à une flopée de membres
     * On peut ajouter plusieurs distinctions en 1 fois, grace à chosen coté frontend
     */
    public function addDistinctionsAction() {
        
        
        //On récupère les infos
        $ids            = $this->get('request')->request->get('membres');
        $distinctions   = $this->get('request')->request->get('distinctions');
        $obtention      = $this->get('request')->request->get('obtention');
        
        $em             = $this->getDoctrine()->getManager();
        $mRepo          = $em->getRepository('InterneFichierBundle:Membre');
        $dRepo          = $em->getRepository('InterneStructureBundle:Distinction');
        
        
        $distinctions = explode(',', $distinctions[0]);
        
        //On récupère chaque membre
        for($i = 0; $i < count($ids); $i++) {
            
            $membre = $mRepo->find($ids[$i]);
            
            //Puis chaque distinction
            for($j = 0; $j < count($distinctions); $j++) {
                
                $dist = $dRepo->find($distinctions[$j]);
                
                $od = new ObtentionDistinction();
                $od->setDistinction($dist);
                $od->setObtention(($obtention == null || $obtention == '') ? new \Datetime('now') : new \Datetime($obtention));
                
                $membre->addDistinction($od);
                $em->persist($od);
            }
        }
        
        $em->flush();
        
        
        return new JsonResponse(1);
    }
    
}
