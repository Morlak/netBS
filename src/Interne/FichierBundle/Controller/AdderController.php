<?php

namespace Interne\FichierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

//Entity
use Interne\FichierBundle\Entity\Membre;

use Interne\StructureBundle\Entity\Attribution;
use Interne\StructureBundle\Entity\Distinction;
use Interne\StructureBundle\Entity\ObtentionDistinction;
use Interne\StructureBundle\Entity\Groupe;
use Interne\StructureBundle\Entity\Fonction;

class AdderController extends Controller
{
	
	/**
	 * La méthode adder va s'occuper de lier des membres par leur id, avec une nouvelle
	 * entité, type attribution, distinction ou facture.
	 * @param ids array contenant les ids des membres dont il faut s'occuper
	 * @param data toutes les informations concernant l'entité à leur lier
	 * 
	 * adder est la fenêtre vers l'ensemble des méthodes d'ajout pré construites
	 * dans ce controller
	 */
    public function adderAction()
    {
    	//Première chose, on génère l'entity en fonction des paramètres indiqués
    	$em = $this->getDoctrine()->getManager();
    	
    	//On récupère les données
    	$donnees = $request->request->get('request');
    	$data	 = $donnees['data'];
    	$ids	 = $donnees['ids'];
    	
    	
    	//Grosse boucle de persistage
    	foreach($ids as $id) {
    		
    		$membre = $em->getRepository('InterneFichierBundle:Membre')->find($id);
    		
    		//En fonction de l'entité à lier, on fait tourner telle ou telle méthode
    		if($data['entity'] == "attribution") 
    			$membre->setAttribution($this->adderAttribution($data));
    		
    		
    		else if($data['entity'] == "distinction") 
    			$membre->setAttribution($this->adderAttribution($data));
    		
    		//On persiste chaque membre concerné
    		$em->persist($membre);
    	}
    	
    	//Une fois la boucle finie, on flush
    	$em->flush();
    	
    	return new JsonResponse($ids);
    }
    
    /**
     * adder d'attribution
     * @param data
     * @entry dateDebut la date de début
     * @entry dateFin 	la date de fin
     * @entry fonction  l'id de la fonction liée
     * @entry groupe    l'id du groupe lié
     */
    private function adderAttribution($data) {
    	
    	$em			 = $this->getDoctrine()->getManager();
    	$attribution = new Attribution();
    	$debut		 = ($data['dateDebut'] == "") ? new \Datetime() : new \Datetime($data['dateDebut']);
    	$fin		 = ($data['dateFin'] == "") ? new \Datetime() : new \Datetime($data['dateFin']);
    	
    	$attribution->setDateDebut($debut);
    	$attribution->setDateFin($fin);
    	$attribution->setGroupe($em->getReference('Groupe', $data['groupe']));
    	$attribution->setFonction($em->getReference('Fonction', $data['fonction']));
    	
    	return $attribution;
    }
    
    /**
     * adder distinction
     * @param data les données sur la distinction
     * @entry distinction l'id de la distinction
     * @entry obtention   la date d'obtention
     */
    private function adderDistinction($data) {
    	
    	$em 		 = $this->getDoctrine()->getManager();
    	$distinction = new ObtentionDistinction();
    	
    	$distinction->setDistinction($em->getReference('Distinction', $data['distinction']));
    	$distinction->setObtention( ($data['obtention'] == "") ? new \Datetime() : new \Datetime($data['obtention']) ); //swag des terniaires
    	
    	return $distinction;
    }
  
}