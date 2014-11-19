<?php

namespace Interne\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Interne\FichierBundle\Entity\Membre;

class SearchController extends Controller
{

    /**
     * fonction provisoir qui a pour but d'afficher la bare de recherche.
     * cette fonction sera supprimée lorsqu'on mettra la barre de recherche
     * dans le template standard.
     */
    public function showSearchBarAction()
    {
        return $this->render('InterneSearchBundle:Search:search.html.twig');
    }

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
        $request = $this->container->get('request');

        if($request->isXmlHttpRequest())
        {
            $str = $request->request->get('searchString');
            $searchElements = explode(' ', $str);

            $em = $this->getDoctrine()->getManager();
            $membres = $em->getRepository('InterneFichierBundle:Membre')->findAll();

            /*
            $membresResults = array();

            foreach($elements as $element) {

                //on commence par les membres seulement
                $membres = $this->getDoctrine()->getRepository('FichierBundle:Personne')->findBy(array('prenom'=>$element));
                $membresResults = array_merge($membresResults, $membres);

            }*/


        }


        return $this->render('InterneSearchBundle:Search:searchResults.html.twig',
            array('searchElements'=>$searchElements, 'membres'=>$membres));




    }
    
    /**
     * effectue une recherche dans un repository
     * en fonction des paramètre fournis à la méthode
     */
    private function searchRepository($repo, $field, $data) {
    	
    	$function = 'findBy' . ucfirst($field);
    	return $repo->$function($data);
    }

    public function searchMembers()
    {

        $infos = array();

        $mRepo = $this->getDoctrine()->getManager()->getRepository('InterneFichierBundle:Membre');

        $members = $mRepo->findAll();

        foreach ($members as $member) {

            $attr = $member->getActiveAttributions();

            $infos[] = array(

                'id' => $member->getId(),
                'prenom' => $member->getPrenom(),
                'nom' => $member->getFamille()->getNom(),
                'fonction' => ($attr[0] != null) ? $attr[0]->getFonction()->getNom() : '',
                'groupe' => ($attr[0] != null) ? $attr[0]->getGroupe()->getNom() : '',
                'numeroBS' => $member->getNumeroBS()
            );
        }

        return new JsonResponse($infos);
    }


}
