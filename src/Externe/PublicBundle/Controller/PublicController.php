<?php

namespace Externe\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

//Entités
use Interne\StammBundle\Entity\Evenement;
use Interne\StammBundle\Entity\News;
use Interne\SecurityBundle\Entity\Role;
use Externe\PublicBundle\Entity\Contact;

//Form
use Externe\PublicBundle\Form\ContactType;

class PublicController extends Controller
{
    
    /**
     * affiche la page d'accueil du site frontal de la BS
     * tellement d'swag et d'argent
     */
    public function accueilAction() {
	
	//On récupère les 3 derniers albums ajoutés à la galerie
	$aRepo = $this->getDoctrine()->getManager()->getRepository('ExterneGalerieBundle:Album');
	$albums = $aRepo->findLastAdded(3);
	return $this->render('ExternePublicBundle:Public:accueil.html.twig', array(
	    
		'albums'	=> $albums
	    )
	);
    }
    
	
    /**
     * Affiche la page de connexion à l'intranet
     */
    public function loginAction()
    {

    	$request = $this->getRequest();
    	$session = $request->getSession();
    	
    	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
    		
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } 
        
        else {
        	
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return $this->render('ExternePublicBundle::login.html.twig', array(
        	
            'error'         => $error,
        ));
        
    }
    
    

}
