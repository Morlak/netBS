<?php

namespace Interne\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller
{
	
	/**
	 * Page d'accueil de l'intranet,
	 * se retrouve ici après authentification
	 * 
	 * La page index affiche le contenu du bundle stammBundle, c'est-à-dire
	 * les news, le calendrier et les téléchargements
	 */
    public function indexAction()
    {
    	
    	$manager  = $this->getDoctrine()->getManager();
    	
    	$newsRepo = $manager->getRepository('InterneStammBundle:News');
    	$ddlRepo  = $manager->getRepository('InterneStammBundle:Download');
    	
    	$newsList = $newsRepo->findNews(3);
    	$ddlList  = $ddlRepo->findFullDownloads();
    	
        return $this->render('InterneHomeBundle:Default:index.html.twig', array(
        	
        		'newsList'		=> $newsList,
        		'ddlList'		=> $ddlList,
        	));
    }
    
    /**
     * Page de recherche, fournit le formulaire complet pour effectuer
     * une recherche avancée, traitement des données par ajax
     */
    public function rechercherAction() {
    	
    	return $this->render('InterneHomeBundle:Default:rechercher.html.twig');
    }
}
