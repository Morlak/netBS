<?php

namespace Interne\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

use Externe\GalerieBundle\Entity\Droit;
use Externe\GalerieBundle\Form\DroitType;

class GlobalController extends Controller
{
    
    /**
     * Méthode magique permettant de modifier l'attribut d'une entité par requête ajax 
     * le système reçoit l'entité ainsi que son nouveau contenu, la modifie, et c'est parti
     * la règle est :
     * bundle.entity.ID.truc.machin, par exemple InterneFichierBundle.famille.4.pere.contact.telephone
     * aussi appelée LE MODIFIKATOR
     */
    public function modifikatorAction($entity, $content) {
    	
    	
    	//Première chose, on récupère les informations sur l'entité
    	$entity = urldecode($entity);
    	$data   = explode('.', $entity);
	$bundle = $data[0];
    	$main   = $data[1]; //Entité mère (famille, membre...)
    	$id     = $data[2];
    	$link   = array();
    	
    	//On formate le content en tant que boolean si nécessaire
    	if($content == 'true')  		$content = true;
    	else if($content == 'false') 		$content = false;
    	else if($content == 'NULL_CONTENT') 	$content = null;
    	else 					$content = urldecode($content);
    	
    	for($i = 0; $i < count($data) - 3; $i++) {
    		
    		$link[$i] = $data[$i + 3]; //On stocke le chemin dans l'entité
    	}
    	
    	//On récupère l'entité avec l'em
    	$em   = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository($bundle . ':' . ucfirst($main));
    	
    	$entity   = $repo->find($id); //entity stockée
    	$res      = $entity;
    	$calls    = count($link) -1;
    	
    	
    	//On parcourt ensuite l'entité en fonction du nombre de paramètres présents dans link
    	
    	for($i = 0; $i < $calls; $i++) { //Le -1 parce que le dernier paramètre ne sera pas get mais set
    		
    		if($calls != 0) {
    			
    		
	    		$function = 'get' . ucfirst($link[$i]);
	    		$res = $res->$function();

    		}
    	}
    	
    	$set = 'set' . ucfirst($link[count($link) - 1]);
	    $res->$set($content);
    	
    	$em->persist($entity);
    	$em->flush();
    	return new Response(1);
    }
}
