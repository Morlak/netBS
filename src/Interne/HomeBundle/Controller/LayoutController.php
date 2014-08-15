<?php

namespace Interne\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//Entity
use Interne\StammBundle\Entity\Evenement;

/**
 * Ce controller est embeded, c'est-à-dire qu'il est appelé depuis le template twig layout
 * afin de fournir les informations sur l'utilisateur directement dans le template principal,
 * comme la gestion dynamique du menu, ou la barre du haut
 */

class LayoutController extends Controller
{
	 
    /**
     * affiche le menu géneré dynamiquement
     */
    public function menuAction() {
    	
    	$user 	      = $this->container->get('security.userInfo')->getUser();
    	$attributions = $user->getAttributions();
	
	/**
	 * on récupère également les groupes d'attributions inscrits dans la
	 * galerie pour le menu automatique
	 */
	$dRepo	 = $this->getDoctrine()->getManager()->getRepository('ExterneGalerieBundle:Droit');
	$droits  = array();
	$c	 = 0;
	
	for($i = 0; $i < count($attributions); $i++) {
	    
	    $potentiel = $dRepo->findByGroupe($attributions[$i]->getGroupe());
	    if(!empty($potentiel[0])) {
	    
		$droits[$c] = $potentiel[0];
		$c++;
	    }
	}
    	
    	
        return $this->render('InterneHomeBundle:Layout:menu.html.twig', array(
        	
        		'user'		=> $user,
			'galeries'	=> $droits
        	));
    }
}
