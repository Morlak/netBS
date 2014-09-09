<?php

namespace Interne\StructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Interne\StructureBundle\Entity\Attribution;
use Interne\StructureBundle\Form\GroupeType;

use Symfony\Component\HttpFoundation\Request;

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
    	$attrs 		= array();
	
    	
    	/**
	 * On passe à la série des IFs qui analysent la requête
	 * %en fonction de la hierarchie et des dates
	 */
	
	//Les deux dates sont vides, on veut tout
	if($date1 == 'empty' && $date2 == 'empty')
	{
	    if($hierarchie == 'main')
		$attrs = $aRepo->findByGroupe($groupe);
		
	    else //On veut aussi les groupes enfants
	    {
		//On récupère la liste des groupes enfants
		$enfants =  $repo->findEnfantsUnordered($groupe);
		
		
		if($hierarchie == 'children') //Pas le groupe courant
		    unset($enfants[0]);
		
		//On récupère ensuite toutes les attributions pour les groupes enfants
		foreach($enfants as $gr) {
		    
		    $attrEnfants = $aRepo->findByGroupe($gr);
		    $attrs = array_merge($attrs, $attrEnfants);
		}
		
	    }
	}

	/**
	 * On veut des dates avec !
	 * Analyse en fonction des dates
	 */
	else
	{
	    
	    /**
	     * premiere chose, si une des deux dates est inexistante, on crée
	     * une date fictive pour réaliser les tests. Par exemple, si la date de début
	     * est inexistante, on met la date de l'an 1000 pour début
	     */
	    
	    $date1 = (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date1)) ? new \Datetime('1000-01-01') : new \Datetime($date1);
	    $date2 = (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date2)) ? new \Datetime('3000-01-01') : new \Datetime($date2);
	    
	    //Ensuite on fait les tests habituels
	    //Seulement ce groupe
	    if($hierarchie == 'main')
		$attrs = $aRepo->findAttributionsForThisGroupe($groupe, $date1, $date2);
	    
	    //enfants inclus
	    else
	    {
		//On récupère la liste des groupes enfants
		$enfants =  $repo->findEnfantsUnordered($groupe);
		
		if($hierarchie == 'children') //Suppression du groupe parent
		    unset($enfants[0]);
		
		//On récupère ensuite les attributions
		foreach($enfants as $gr) {
		    
		    $attrEnfants = $aRepo->findByGroupe($gr);
		    $attrs = array_merge($attrs, $attrEnfants);
		}
		
	    }
	}
	
    	
    	//A ce stade, on a récupéré l'ensemble des attributions qui nous interessaient, on en
    	//tire les données souhaitées
    	$finales	= array();
    	$c 			= 0;
    	
    	foreach($attrs as $attr) {
    		
	    $finales[$c]['id'] 			= $attr->getId();
	    $finales[$c]['membreId'] 		= $attr->getMembre()->getId();
	    $finales[$c]['membreNom']		= $attr->getMembre()->getFamille()->getNom();
	    $finales[$c]['familleId']		= $attr->getMembre()->getFamille()->getId();
	    $finales[$c]['membrePrenom'] 	= $attr->getMembre()->getPrenom();
	    $finales[$c]['fonction'] 		= $attr->getFonction()->getNom();
	    $finales[$c]['groupe'] 		= $attr->getGroupe()->getNom();
	    $finales[$c]['groupeId'] 		= $attr->getGroupe()->getId();
	    $finales[$c]['dateDebut'] 		= $attr->getDateDebut()->format('d.m.Y');
	    $finales[$c]['dateFin'] 		= ($attr->getDateFin() != null || $attr->getDateFin() != '') ? $attr->getDateFin()->format('d.m.Y') : '';
	    $c++;

    	}
    	
    	return new JsonResponse($finales);
    }
    
    
    /**
     * permet d'ajouter une attribution en fonction de l'ID du membre
     */
    public function addAttributionsAction($id) {
	
	
	$em 		= $this->getDoctrine()->getManager();
	
	$membre		= $em->getRepository('InterneFichierBundle:Membre')->find($id);
	$gRepo		= $em->getRepository('InterneStructureBundle:Groupe');
	$fRepo		= $em->getRepository('InterneStructureBundle:Fonction');
	$data		= $this->get('request')->request->get('donnees');
	
	//On crée les attributions
	for($i = 0; $i < count($data); $i++) {
	    
	    $attribution = new Attribution();
	    
	    $fonction = $fRepo->find($data[$i]['fonction']);
	    $groupe   = $gRepo->find($data[$i]['groupe']);
	    
	    $debut    = (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $data[$i]['debut'])) ? new \Datetime("now") : new \Datetime($data[$i]['debut']);
	    $fin      = (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $data[$i]['fin'])) ? null : new \Datetime($data[$i]['fin']);
	    
	    $attribution->setGroupe($groupe);
	    $attribution->setFonction($fonction);
	    $attribution->setDateDebut($debut);
	    $attribution->setDateFin($fin);
	    
	    $membre->addAttribution($attribution);
	    $em->persist($attribution);
	}
	
	$em->flush();
	
	return new JsonResponse(1);
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
    
    /**
     * la méthode ajaxModifyAttribution permet de modifier une attribution existante
     * avec les données POST récupérées
     */
    public function AJAXModifyAttributionAction() {
	
	$id 		= $this->get('request')->request->get('id');
	$donnees 	= $this->get('request')->request->get('donnees');
	
	//On nettoie et récupère
	$em 		= $this->getDoctrine()->getManager();
	$attribution	= $em->getRepository('InterneStructureBundle:Attribution')->find($id);
	$fonction	= $em->getRepository('InterneStructureBundle:Fonction')->find($donnees[0]);
	$groupe		= $em->getRepository('InterneStructureBundle:Groupe')->find($donnees[1]);
	
	//On modifie l'attribution
	$attribution->setFonction($fonction);
	$attribution->setGroupe($groupe);
	
	//Si la date de début était vide, on la modifie pas
	if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $donnees[2]))
	    $attribution->setDateDebut(new \Datetime($donnees[2]));
	    
	if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $donnees[3]))
	    $attribution->setDateFin(new \Datetime($donnees[3]));
	
	$em->persist($attribution);
	$em->flush();
	
	//On envoie comme reponse les informations à afficher
	$return = array(
	    
	    'fonction' => $fonction->getNom(),
	    'groupeId' => $groupe->getId(),
	    'groupeN'  => ucfirst($groupe->getNom()),
	    'debut'    => $attribution->getDateDebut()->format('d.m.Y'),
	    'fin'      => ($attribution->getDateFin() == null) ? '' : $attribution->getDateFin()->format('d.m.Y')
	);
	
	return new JsonResponse($return);
    }
    
}
