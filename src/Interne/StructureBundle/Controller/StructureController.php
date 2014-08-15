<?php

namespace Interne\StructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Interne\StructureBundle\Entity\Groupe;
use Interne\StructureBundle\Form\GroupeType;

class StructureController extends Controller
{
	
	/**
	 * Offre la vue de la hierarchie du groupe, ainsi que les divers options, comme
	 * ajouter un groupe enfant à un autre groupe etc...
	 */
	
    public function hierarchieAction()
    {
    	$groupe  = new Groupe();
	$form 	 = $this->createForm(new GroupeType, $groupe);
	$em      = $this->getDoctrine()->getManager();
	$session = new Session();
	
	//On valide un éventuel formulaire d'ajout de groupe
	if ($this->getRequest()->isMethod('POST')) {
		
		$form->bind($this->getRequest());
		
		if ($form->isValid()) {
			
		    //On persiste le nouveau groupe
		    $em->persist($groupe);
		    $em->flush();
		    $session->getFlashBag()->add('notice', 'Groupe ajouté avec succès');
		}
		
		else
		    $session->getFlashBag()->add('error', 'Erreur lors de l\'ajout du groupe');
	}
	
	
        return $this->render('InterneStructureBundle:Structure:hierarchie.html.twig', array(
	    
	    'groupeForm'	=> $form->createView()
        ));
    }
    
    /**
     * La méthode AJAXgetFullHierarchieAction retourne la hierarchie complète du groupe pour
     * affichage, vu que l'action de récupération est longue, on fait ca en ajax
     */
    public function AJAXGetFullHierarchieAction() {
    	
    	
    	$em 		= $this->getDoctrine()->getManager();
    	$repo 		= $em->getRepository('InterneStructureBundle:Groupe');
	$main		= $repo->findMainParent()[0];
	
	return new JsonResponse(array(
	    
	    'icon' 	=> '/netBS/web/static/images/layout/group.png',
	    'text'	=> $main->getNom(),
	    'children'	=> $repo->findJsonHierarchie($main),
	    'li_attr'	=> array('groupe_id' => $main->getId()),
	    'state'	=> array('opened' => true)
	));
    }
    
    /**
     * Permet d'afficher un groupe, ses informations etc.
     */
    public function voirGroupeAction($id) {
    	
    	$em     	= $this->getDoctrine()->getManager();
    	$gRepo    	= $em->getRepository('InterneStructureBundle:Groupe');
    	$groupe 	= $gRepo->find($id);
    	
    	//On récupère les attributions liées à ce groupe
    	$aRepo		= $em->getRepository('InterneStructureBundle:Attribution');

    	//On récupère également l'ensemble des attributions liées à ce groupe pour afficher
    	//les membres courants, donc également trouver les membres des groupes enfants possibles
    	$attrs		= $aRepo->findCurrentAttributionsForThisGroupe($groupe);

    	
    	return $this->render('InterneStructureBundle:Structure:voir_groupe.html.twig', array(
    		
    		'groupe'		=> $groupe,
    		'attrs'			=> $attrs
    	));
    }
    
}
