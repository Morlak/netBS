<?php

namespace Interne\StammBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

//Entity
use Interne\StammBundle\Entity\News;
use Interne\StammBundle\Entity\Download;
use Interne\StammBundle\Entity\Evenement;

//Forms
use Interne\StammBundle\Form\NewsType;
use Interne\StammBundle\Form\EvenementType;
use Interne\StammBundle\Form\DownloadType;

class StammController extends Controller
{
    
    
    

    
    /**
     * Permet de renvoyer le fichier à télécharger, renvoyant ainsi les bon
     * headers
     */
    public function downloadFileAction($file) {
    	
    	
    	//Récupération de l'entité auprès de Doctrine
    	$repo 	  = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:Download');
    	$ddl  	  = $repo->findOneBy(array('path' => $file));
        $content  = file_get_contents($ddl->getAbsolutePath());
        
        //On doit récupérer l'extension du fichier
        $brutName = $ddl->getPath();
        $ext	  = explode('.', $brutName);
        
        $response = new Response();

		//Déclaration des headers
        $response->headers->set('Content-Type', $ddl->getType());
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$ddl->getNom() . '.' . $ext[1]);
        
        //Renvoi de la reponse
        $response->setContent($content);
        return $response;
    }
    
    /**
     * -- DASHBOARD --
     * Affichage du dashboard
     * Le dashboard permet à l'utilisateur ayant les droits nécessaires de :
     * - ajouter/gérer les news
     * - ajouter/gérer les téléchargements
     * - gérer l'agenda
     */
    public function dashboardShowAction() {

        // On instancie les entities
    	$news 	= new News();
    	$event 	= new Evenement();
    	$ddl 	= new Download();
    	
    	// récupération des différents formulaires
    	$newsForm 	= $this->createForm(new NewsType, $news);
    	$eventForm 	= $this->createForm(new EvenementType, $event);
    	$ddlForm 	= $this->createForm(new DownloadType, $ddl);
    	
    	// récupération de la liste de chaque entité
    	$newsRepository  = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:News');
    	$eventRepository = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:Evenement');
    	$ddlRepository 	 = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:Download');
    	
    	
		//Envoi de la vue
    	return $this->render('InterneStammBundle:Stamm:dashboard.html.twig', array(
    		
    		'newsForm'		=> $newsForm->createView(),
    		'eventForm'		=> $eventForm->createView(),
    		'ddlForm'		=> $ddlForm->createView(),
    		'ddlList'		=> $ddlRepository->findAll(),
    		'eventList'		=> $eventRepository->findAll(),
    		'newsList'		=> $newsRepository->findAll()
    		
    	));
    }
    
    /**
     * Permet d'ajouter une entité parmi News, Evenement et Download en fonction de
     * l'entité passée au controller
     */
    public function dashboardAddAction($entity) {
    	
    	if($entity != 'News' && $entity != 'Evenement' && $entity != 'Download') 
    		throw $this->createNotFoundException('Accès impossible, données transmises incompatibles');

        $persistor = $this->get('global.persistor');

        //On instancie l'entité
    	$nspc = 'Interne\StammBundle\Entity' . '\\' . $entity;
    	$type = 'Interne\StammBundle\Form' . '\\' . $entity . 'Type';
    	$obj  = new $nspc();
    	$form = $this->createForm(new $type(), $obj);
    	
    	if ($this->getRequest()->isMethod('POST')) {
			
			$form->bind($this->getRequest());
			if ($form->isValid()) {
				
				//On persiste l'objet
			    $em = $this->getDoctrine()->getManager();

                if($entity == 'Download') $em->persist($obj);
				else $persistor->safePersist($obj);
				$em->flush();
			}
		}
		
		// On retourne de toute facon vers le dashboard
		return $this->redirect($this->generateUrl('InterneStamm_dashboard'));

    }
    
    /**
     * Permet de supprimer l'entité passée en paramètre
     */
    public function dashboardRemoveAction($entity, $id) {
    	
    	if($entity != 'News' && $entity != 'Download' && $entity != 'Evenement')
    		throw $this->createNotFoundException('Paramètres passés incorects');
    		
    		
    	// on chope le repository et l'entity à supprimer
    	$repo   = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:' . $entity);
    	$entity = $repo->find($id);
    	
    	$em = $this->getDoctrine()->getEntityManager();
	    $em->remove($entity);
	    $em->flush();
	    
	    //On redirige vers le dashboard
	    return $this->redirect($this->generateUrl('InterneStamm_dashboard'));
    }
    
}
