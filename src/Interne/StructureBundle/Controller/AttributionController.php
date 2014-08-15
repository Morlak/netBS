<?php

namespace Interne\StructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Interne\StructureBundle\Entity\Groupe;
use Interne\StructureBundle\Form\GroupeType;

class AttributionController extends Controller
{
	
	
	/**
	 * offre la vue de gestion des attributions, fournis l'interface pour gérer l'ensemble
	 * des fonctions disponibles également
	 */
	public function attributionsFonctionsAction() {
		
	    //Récupération de l'ensemble des fonctions
	    $em 	= $this->getDoctrine()->getManager();
	    $fRepo  	= $em->getRepository('InterneStructureBundle:Fonction');
	    $fonctions 	= $fRepo->findAll();
	    
	    //On récupère la liste des groupes pour le select des attributions
	    $data		= array();
	    $groupeList = $this->createFormBuilder($data)
		->add('groupe', 'entity', array(
		     
			'class'	=> 'InterneStructureBundle:Groupe',
			'property'	=> 'nom',
			'attr'	=> array('class' => 'groupeId')
		    ))
		->getForm();

	    
	    return $this->render('InterneStructureBundle:Structure:attributions_fonctions.html.twig', array(
		    
			    'fonctions'	 => $fonctions,
			    'groupeList' => $groupeList->createView()
		    ));
	}
	
	
	 /**
     * Permet de récupérer l'ensemble des attributions d'un groupe et de ses groupes
     * enfants, en fonction des nombreux paramètres passés, et du temps
     */
    public function AJAXGetAttributionsGroupesAction($id, $hierarchie, $date1, $date2) {
    	
    	$em     	= $this->getDoctrine()->getManager();
    	$repo		= $em->getRepository('InterneStructureBundle:Groupe');
    	$groupe		= $repo->find($id);
    	$aRepo		= $em->getRepository('InterneStructureBundle:Attribution');
    	$attrs;
    	
    	$date1		= ($date1 == "empty") ? new \Datetime("1900-01-01") : new \Datetime($date1);
    	$date2		= ($date2 == "empty") ? new \Datetime("2200-01-01") : new \Datetime($date2);
    	
    	
    	//Première chose à faire : regarder si on désire les attributions du groupe courant,
    	//ou celles de ses enfants
    	if($hierarchie == "main") //groupe courant
    	{
    		//On ne souhaite que les attributions du groupe courant
    		$attrs = $aRepo->findAttributionsForThisGroupe($groupe, $date1, $date2);
    	}
    	
    	else {  //On souhaite les atributions des groupes enfants
    		
    		//On récupère la liste des groupes enfants du groupe concerné
	    	$enfants = $repo->findEnfantsUnordered($groupe);
	    	
	    	if($hierarchie == "children") //Groupe parent exclu
	    		unset($enfants[0]);
	    		
	    	
	    	//On récupère la liste des attributions en fonction du temps
	    	$attrs = $aRepo->findAttributionsForGroupes($enfants, $date1, $date2);
    	}
	
    	
    	//A ce stade, on a récupéré l'ensemble des attributions qui nous interessaient, on en
    	//tire les données souhaitées
    	$finales	= array();
    	$c 			= 0;
    	
    	foreach($attrs as $attr) {
    		
	    $finales[$c]['id'] 				= $attr->getId();
	    $finales[$c]['membreId'] 		= $attr->getMembre()->getId();
	    $finales[$c]['membreNom']		= $attr->getMembre()->getFamille()->getNom();
	    $finales[$c]['familleId']		= $attr->getMembre()->getFamille()->getId();
	    $finales[$c]['membrePrenom'] 	= $attr->getMembre()->getPrenom();
	    $finales[$c]['fonction'] 		= $attr->getFonction()->getNom();
	    $finales[$c]['groupe'] 			= $attr->getGroupe()->getNom();
	    $finales[$c]['groupeId'] 		= $attr->getGroupe()->getId();
	    $finales[$c]['dateDebut'] 		= $attr->getDateDebut()->format('d.m.Y');
	    $finales[$c]['dateFin'] 		= $attr->getDateFin()->format('d.m.Y');
	    $c++;

    	}
    	
    	return new JsonResponse($finales);
    }
    
    
    /**
     * Permet de supprimer de manière définitive une attribution
     */
    public function removeAttributionAction($attributionId) {
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$attribution = $em->getRepository('InterneStructureBundle:Attribution')->find($attributionId);
    	
    	$membreId = $attribution->getMembre()->getId();
    	$em->remove($attribution);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('InterneFichier_voir_membre', array('id' => $membreId)));
    }
    
}
