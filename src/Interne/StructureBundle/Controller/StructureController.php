<?php

namespace Interne\StructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Interne\StructureBundle\Entity\Groupe;
use Interne\StructureBundle\Entity\Type;
use Interne\StructureBundle\Form\GroupeType;
use Interne\StructureBundle\Form\TypeType;

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

        //On récupère aussi le formulaire de type
        $type = new Type();
        $typeForm = $this->createForm(new TypeType, $type);

        return $this->render('InterneStructureBundle:Structure:hierarchie.html.twig', array(
	    
	    'groupeForm'	=> $form->createView(),
	    'typeForm'		=> $typeForm->createView()
        ));
    }
    
    /**
     * permet d'ajouter un groupe
     */
    public function addGroupeAction() {

        $groupe  = new Groupe();
        $form 	 = $this->createForm(new GroupeType, $groupe);
        $em      = $this->getDoctrine()->getManager();
        $session = new Session();

        //On valide un éventuel formulaire d'ajout de groupe
        if ($this->getRequest()->isMethod('POST')) {

            $form->bind($this->getRequest());

            if ($form->isValid()) {

                //On persiste le nouveau groupe
                $persistor = $this->get('global.persistor');
                $persistor->safePersist($groupe);

                //$em->persist($groupe);
                $em->flush();

                if($persistor->hasValidatorRoles())
                    $session->getFlashBag()->add('notice', 'Groupe ajouté avec succès');
                else
                    $session->getFlashBag()->add('notice', "Une demande d'ajout de groupe a été envoyée");
            }

            else
                $session->getFlashBag()->add('error', 'Erreur lors du traitement des données');
        }

        return $this->redirect($this->generateUrl('InterneStructure_hierarchie'));
    }
    
    /**
     * permet d'ajouter un type de groupe
     */
    public function addTypeAction() {
	
	$em      = $this->getDoctrine()->getManager();
	$session = new Session();
	
	//On récupère aussi le formulaire de type
	$type = new Type();
	$typeForm = $this->createForm(new TypeType, $type);
	
	if ($this->getRequest()->isMethod('POST')) {
		
		$typeForm->bind($this->getRequest());
		
		
		if($typeForm->isValid() ) {

            $persistor = $this->get('global.persistor');
		    $persistor->safePersist($type);
		    $em->flush();
		    $session->getFlashBag()->add('notice', 'Type ajouté avec succès');
		}
		
		else
		    $session->getFlashBag()->add('error', 'Erreur lors du traitement des données');
	}
	
	return $this->redirect($this->generateUrl('InterneStructure_hierarchie'));
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
	
        $troupe = false;

        //Si le groupe en question est un troupe
        //alors on affiche la barre d'action spéciale troupe
        if($groupe->getType() != null && strtolower($groupe->getType()->getNom()) == 'troupe') {

            $troupe = true;
        }

    	//On récupère également l'ensemble des attributions liées à ce groupe pour afficher
    	//les membres courants, donc également trouver les membres des groupes enfants possibles
        $attrs		= $aRepo->findCurrentAttributionsForThisGroupe($groupe);

    	return $this->render('InterneStructureBundle:Structure:voir_groupe.html.twig', array(
    		
    		'groupe'		=> $groupe,
    		'attrs'			=> $attrs,
		    'isTroupe'		=> $troupe
    	));
    }
    
}
