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
	 * le bundle externe offre uniquement l'interface de connexion, deconnexion
	 * cette méthode permet d'afficher le formulaire de connexion
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
