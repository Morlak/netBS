<?php

namespace Externe\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

use Interne\FichierBundle\Entity\Membre;
use Interne\FichierBundle\Entity\Geniteur;
use Interne\FichierBundle\Entity\Famille;
use Interne\FichierBundle\Entity\Adresse;
use Interne\SecurityBundle\Entity\User;

use Interne\StructureBundle\Entity\Groupe;
use Interne\StructureBundle\Entity\Type;
use Interne\StructureBundle\Entity\Attribution;
use Interne\StructureBundle\Entity\Fonction;

class PublicController extends Controller
{
    
    /**
     * affiche la page d'accueil du site frontal de la BS
     * tellement d'swag et d'argent
     */
    public function accueilAction() {
	
	    return $this->redirect($this->generateUrl('login'));


/*
        $membre = new Membre();
        $adresse = new Adresse();
        $user = new User();
        $famille = new Famille();
        $mere = new Geniteur();
        $pere = new Geniteur();


        $groupe = new Groupe();
        $type = new Type();
        $attribution = new Attribution();
        $fonction = new Fonction();

        $adresse->setRue('Chemin des planches 1');
        $adresse->setNpa(1073);
        $adresse->setLocalite('Savigny');
        $adresse->setFacturable(true);

        $pere->setPrenom('Bertrand');
        $pere->setSexe('homme');
        $pere->setProfession('Ingénieur');

        $mere->setPrenom('Catherine');
        $mere->setProfession('Physio');
        $mere->setSexe('femme');


        $famille->setAdresse($adresse);
        $famille->setNom('Hochet');
        $famille->setMere($mere);
        $famille->setPere($pere);

        $membre->setSexe('homme');
        $membre->setPrenom('Guillaume');
        $membre->setFamille($famille);
        $membre->setNaissance(new \Datetime('1994-04-09'));
        $membre->setInscription(new \Datetime());

        $type->setNom('Brigade');
        $groupe->setNom('Brigade de Sauvabelin');
        $groupe->setType($type);

        $fonction->setAbreviation('adj');
        $fonction->setNom('Adjoint');

        $attribution->setDateDebut(new \Datetime('2011-01-12'));
        $attribution->setDateFin(new \Datetime('2014-01-14'));
        $attribution->setGroupe($groupe);
        $attribution->setMembre($membre);
        $attribution->setFonction($fonction);

        $em = $this->getDoctrine()->getManager();
        $em->persist($membre);
        $em->persist($famille);
        $em->persist($groupe);
        $em->persist($type);
        $em->persist($fonction);
        $em->persist($attribution);


        $em->flush();
*/
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
