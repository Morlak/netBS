<?php

namespace Interne\FichierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//Entity
use Interne\FichierBundle\Entity\Membre;
use Interne\FichierBundle\Entity\Famille;

use Interne\StructureBundle\Entity\Attribution;
use Interne\StructureBundle\Entity\ObtentionDistinction;

//Forms
use Interne\FichierBundle\Form\MembreType;
use Interne\FichierBundle\Form\MembreContactType;
use Interne\FichierBundle\Form\MembreFamilleType;
use Interne\FichierBundle\Form\FamilleType;

use Interne\StructureBundle\Form\AttributionType;
use Interne\StructureBundle\Form\ObtentionDistinctionType;

class FichierController extends Controller
{
	
	/**
	 * La méthode créer membre se contente d'afficher la page d'un membre, mais où tous les champs sont vides,
     * et stylisés pour être facilement modifiables
	 */
    public function creerMembreAction()
    {
        $membre                 = new Membre();
        $famille                = new Famille();

        $membreForm             = $this->createForm(new MembreType, $membre);
        $membreContactForm      = $this->createForm(new MembreContactType, $membre);
        $membreFamilleForm      = $this->createForm(new MembreFamilleType, $membre);
        $familleForm            = $this->createForm(new FamilleType, $famille);

        return $this->render('InterneFichierBundle:Fichier:voir_membre.html.twig', array(

            'membre'            => $membre,
            'membreForm'        => $membreForm->createView(),
            'membreContactForm' => $membreContactForm->createView(),
            'membreFamilleForm' => $membreFamilleForm->createView(),
            'familleForm'       => $familleForm->createView(),
        ));
    }
    
    
    
    /**
     * Permet de visualiser les informations sur une famille
     * @param id l'id de la famille
     */
    public function voirFamilleAction($id) {
    	
    	$repository 	= $this->getDoctrine()->getManager()->getRepository('InterneFichierBundle:Famille');
		$mRepo			= $this->getDoctrine()->getManager()->getRepository('InterneFichierBundle:Membre');
    	$famille		= $repository->find($id);
	
		//On récupère les attributions courantes des membres
		$membres 		= array();
		
		foreach($famille->getMembres() as $key => $membre) {
			
			$membres[$key]['membre'] = $membre;
			$membres[$key]['attribution'] = $mRepo->findCurrentAttribution($membre->getId());
		}
	
    	
    	
    	return $this->render('InterneFichierBundle:Fichier:voir_famille.html.twig', array('famille' => $famille, 'membres' => $membres));
    }
    
    
    /**
     * Permet de visualiser la fiche d'un membre
     * @param id l'id du membre dans la base de données
     */
    public function voirMembreAction($id) {
	
    	$em			= $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('InterneFichierBundle:Membre');
    	$membre		= $repository->find($id);

        $famille    = $membre->getFamille();

    	$hierarchie = array();
    	$compteur   = 0;

        $membreForm = $this->createForm(new MembreType, $membre);
        $membreContactForm = $this->createForm(new MembreContactType, $membre);
        $membreFamilleForm = $this->createForm(new MembreFamilleType, $membre);

        $familleForm = $this->createForm(new FamilleType, $famille);
    	
    	//Récupération des hierarchies de groupes pour chaque attribution si il y en a
    	foreach($membre->getAttributions() as $attribution) {
    		
    		// On récupère seulement les hierarchies des attributions valides (non expirées)
    		$hierarchie[$compteur] = $em->getRepository('InterneStructureBundle:Groupe')->findHierarchie($attribution->getGroupe());
    		$compteur++;
    	}
    	
    	
    	//Récupération du formulaire d'attribution
    	$attribution 	 = new Attribution;
    	$attributionForm = $this->createForm(new AttributionType, $attribution);
    	
    	//Récupération du formulaire d'obtention de distinction
    	$obtentionDistinction 		= new ObtentionDistinction;
    	$obtentionDistinctionForm 	= $this->createForm(new ObtentionDistinctionType, $obtentionDistinction);
    	
    	if ($this->getRequest()->isMethod('POST')) {
			
			///On vérifie quel formulaire a été soumis
			$attributionForm->bind($this->getRequest());
			$obtentionDistinctionForm->bind($this->getRequest());
			
			if ($attributionForm->isValid()) {
				
				$membre->addAttribution($attribution);
				$em->persist($membre);
				$em->flush();
				
				//On redirige si le formulaire a bien été validé
				return $this->redirect($this->generateUrl('InterneFichier_voir_membre', array('id' => $membre->getId())));
			}
			
			//Ajout d'une distinction pour le membre
			if ($obtentionDistinctionForm->isValid()) {
				
				$membre->addDistinction($obtentionDistinction);
				$em->persist($membre);
				$em->flush();
				
				//On redirige si le formulaire a bien été validé
				return $this->redirect($this->generateUrl('InterneFichier_voir_membre', array('id' => $membre->getId())));
			}
		}
		
		/**
		 * la récupération de l'adresse principale se déroule ainsi :
		 * - si le membre a une adresse, on affiche celle-ci
		 * - sinon on affiche celle de la famille
		 * Pour toute personne, on procède ainsi
		 */
		$mainAdresse = $membre->getAdressePrincipale();
		
		//On doit aussi génerer la liste personnalisée des fonctions pour en créer, on récupère donc la liste
		$fonctions = $em->getRepository('InterneStructureBundle:Fonction')->findAll();
		
		//Ainsi que la liste des groupes
		$groupes   = $em->getRepository('InterneStructureBundle:Groupe')->findAll();
		
    	
    	
    	return $this->render('InterneFichierBundle:Fichier:voir_membre.html.twig', array(
                'membreForm'                => $membreForm->createView(),
                'membreContactForm'         => $membreContactForm->createView(),
                'membreFamilleForm'         => $membreFamilleForm->createView(),
                'familleForm'		        => $familleForm->createView(),

    			'membre'	  		        => $membre,
    			'hierarchies'			    => $hierarchie,
    			'attributionForm'		    => $attributionForm->createView(),
    			'obtentionDistinctionForm'	=> $obtentionDistinctionForm->createView(),
    			'adressePrincipale'		    => $mainAdresse,
                'fonctions'			        => $fonctions,
                'groupes'			        => $groupes,
    		));
    }
  
}