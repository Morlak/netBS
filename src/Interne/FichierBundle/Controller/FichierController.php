<?php

namespace Interne\FichierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

//Entity
use Interne\FichierBundle\Entity\Membre;
use Interne\FichierBundle\Entity\Famille;

use Interne\StructureBundle\Entity\Attribution;
use Interne\StructureBundle\Entity\ObtentionDistinction;

//Forms
use Interne\FichierBundle\Form\MembreType;
use Interne\FichierBundle\Form\FamilleType;

use Interne\StructureBundle\Form\AttributionType;
use Interne\StructureBundle\Form\ObtentionDistinctionType;

class FichierController extends Controller
{
	
	/**
	 * Génère la page qui permet d'ajouter une fiche membre
	 * Affichage du formulaire d'ajout d'un membre
	 * 
	 * Le paramètre id est facultatif, si présent, il indique la famille à laquelle
	 * ajouter le membre
	 */
    public function creerMembreAction()
    {
    	
    	//Récupération des entities
    	$membre	 = new Membre;
    	$famille = new Famille;

    	$membreForm  = $this->createForm(new MembreType, $membre);
    	$familleForm = $this->createForm(new FamilleType, $famille);
    	
    	
    	//On vérifie si on a ajouté un nouveau membre à la famille aight
    	if ($this->getRequest()->isMethod('POST')) {
			
			$membreForm->bind($this->getRequest());
			
			if ($membreForm->isValid()) {
				
				$em = $this->getDoctrine()->getManager();
    			
				
				//Le formulaire est valide, on vérifie si la famille était
				//déjà enregistrée, ou si on en a créé une nouvelle pour l'occasion
				if($membreForm->getData()->getFamille() == null) {
					
					//On persiste d'abord le formulaire de famille
					$familleForm->bind($this->getRequest());
					if($familleForm->isValid() ) {
						
						
					   	//L'entité famille est prête, on y ajoute le membre
					   	$famille->addMembre($membre);
					   	
					   	//on ajoute le sexe des parents
					   	$famille->getMere()->setSexe('f');
					   	$famille->getPere()->setSexe('m');
					   	
					   	//On insère la famille dans le membre
					   	$membre->setFamille($famille);
					   	$em->persist($famille);
					}
					
				}
				
				//On génère une attribution de membre pour le nouveau avec le groupe
				//passé
				$attribution = new Attribution;
				
				//on lui file son groupe lié
				$grpid  = $this->getRequest()->request->all()['interne_fichierbundle_membretype']['groupe'];
				$groupe = $em->getRepository('InterneStructureBundle:Groupe');
				$attribution->setGroupe($groupe->find($grpid));
				$attribution->setDateDebut("now");

				//La fonction de membre de base -> abreviartion M
				$fonction = $em->getRepository('InterneStructureBundle:Fonction')->findByAbreviation('M');
				$attribution->setFonction($fonction[0]);
				
				
				//On ajoute l'abreviation au membre
				$membre->addAttribution($attribution);
				
				$em->persist($membre);
				$em->flush();
				
			}
		}
		
    	
        return $this->render('InterneFichierBundle:Fichier:creer_fiche.html.twig', array(
        	
        		'membreForm'		=> $membreForm->createView(),
        		'familleForm'		=> $familleForm->createView()
        	));
    }
    
    
    
    /**
     * Permet de visualiser les informations sur une famille
     * @param id l'id de la famille
     */
    public function voirFamilleAction($id) {
    	
    	$repository = $this->getDoctrine()->getManager()->getRepository('InterneFichierBundle:Famille');
    	$famille	= $repository->find($id);
    	
    	
    	return $this->render('InterneFichierBundle:Fichier:voir_famille.html.twig', array('famille' => $famille));
    }
    
    
    /**
     * Permet de visualiser la fiche d'un membre
     * @param id l'id du membre dans la base de donnée
     */
    public function voirMembreAction($id) {
    	
    	$em			= $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('InterneFichierBundle:Membre');
    	$membre		= $repository->find($id);
    	$hierarchie = array();
    	$compteur   = 0;
    	
    	
    	//Récupération des hierarchies de groupes pour chaque attribution si il y en a
    	foreach($membre->getAttributions() as $attribution) {
    		
    		//On récupère seulement les hierarchies des attributions valides (non expirées)
    		
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
		$mainAdresse = $this->container->get('interne_fichier.adressePrincipale')->checkPrincipale($membre);
		
    	
    	
    	return $this->render('InterneFichierBundle:Fichier:voir_membre.html.twig', array(
    			
    			'membre'	  		=> $membre,
    			'hierarchies'			=> $hierarchie,
    			'attributionForm'		=> $attributionForm->createView(),
    			'obtentionDistinctionForm'	=> $obtentionDistinctionForm->createView(),
    			'adressePrincipale'		=> $mainAdresse,
    		));
    }
  
}