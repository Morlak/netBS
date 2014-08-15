<?php

namespace Interne\StammBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;


class AjaxController extends Controller
{
    
    /**
     * retourne l'ensemble des news réponse ajax
     */
    public function getAllNewsAction() {
    	
    	$liste = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:News')->findAllNews();
    	
    	return new JsonResponse($liste);
    }
    
    /**
     * Exécutée uniquement en requête ajax, permet de mettre à jour l'agenda
     * pour le mois passé en paramètre
     */
    public function agendaUpdateAction($month, $year)
    {
    	
    	$repository = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:Evenement');
    	$events     = $repository->findMonthYear($month, $year);
    	
    	$response = array(
    		
    		'listeEvents' => $events
    	);
    	
    	return new JsonResponse($response);
    }
}
