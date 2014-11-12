<?php

namespace Interne\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    	$user   = $this->container->get('security.userInfo')->getUser();

        return $this->render('InterneHomeBundle:Layout:menu.html.twig', array(
        	
        	'user'		=> $user
        ));
    }
}
