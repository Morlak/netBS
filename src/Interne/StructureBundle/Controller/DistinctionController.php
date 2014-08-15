<?php

namespace Interne\StructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Interne\StructureBundle\Entity\Distinction;
use Interne\StructureBundle\Form\DistinctionType;

class DistinctionController extends Controller
{
	/**
	 * Affiche la page de gestion des distinctions, ainsi que la possibilité
	 * d'en ajouter
	 */
	public function distinctionsAction() {
		
		$em   		= $this->getDoctrine()->getManager();
		$repo 		= $em->getRepository('InterneStructureBundle:Distinction');
		$distinctions 	= $repo->findAll();
		
		//Récupération du formulaire
		$distinction	= new Distinction();
		$form		= $this->createForm(new DistinctionType, $distinction);
		
		//On valide un éventuel formulaire d'ajout de distinction
		if ($this->getRequest()->isMethod('POST')) {
			
			$form->bind($this->getRequest());
			
			if ($form->isValid()) {
				
				//On persiste la nouvelle distinction
				$em->persist($distinction);
				$em->flush();
			}
		}
		
		return $this->render('InterneStructureBundle:Structure:distinctions.html.twig', array(
			
				'distinctions'		=> $distinctions,
				'distinctionForm'	=> $form->createView(),
			));
	}
}
