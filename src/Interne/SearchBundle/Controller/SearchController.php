<?php

namespace Interne\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    
    /**
     * Méthode permettant d'effectuer des recherches croisées
     * en analysant les éléments de recherche
     * 
     * Fonctionne en suivant un ordre :
     * 1. Analyse des champs les uns après les autres pour voir leur type
     * 2. passage dans les tables par ordre d'importance en fonction du type
     * 3. mise en commun des résultats
     */
    public function searchAction()
    {
        $str = "guillaume montfort";
        
        //On initialise les variables de base
        $em			= $this->getDoctrine()->getManager();
        
        
        //On récupère chaque morceau d'information
        $elements = explode(' ', $str);
        
        foreach($elements as $element) {
        	
        	//On analyse le type de donnée
        	if(!is_numeric($element)) //La donnée est une string
        	{
        		/**
        		 * ordre de recherche des string :
        		 * 1. prenom membre
        		 * 2. nom famille
        		 * 3. nom groupe
        		 */
        		
        		
        	}
        }
        
        return new Response("<body></body>");
    }
    
    /**
     * effectue une recherche dans un repository
     * en fonction des paramètre fournis à la méthode
     */
    private function searchRepository($repo, $field, $data) {
    	
    	$function = 'findBy' . ucfirst($field);
    	return $repo->$function($data);
    }
}
