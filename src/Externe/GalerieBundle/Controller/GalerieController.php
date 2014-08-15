<?php

namespace Externe\GalerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GalerieController extends Controller
{
    /**
     * démarre la galerie, sachant que l'ensemble de son fonctionnement
     * tourne en ajax. l'ensemble des requêtes est donc renvoyé sous JSON
     */
    public function launcherAction()
    {
        return $this->render('ExterneGalerieBundle:Galerie:main.html.twig');
    }
    
    /**
     * le first call renvoie le contenu du groupe parent principal, ainsi qu'une liste
     * d'images à afficher
     */
    public function firstCallAction() {
        
        $em         = $this->getDoctrine()->getManager();
        $albumRepo  = $em->getRepository('ExterneGalerieBundle:Album');
        $droitRepo  = $em->getRepository('ExterneGalerieBundle:Droit');
        
        //On récupère les dossiers n' shit du premier droit, c'est-à-dire le
        //droit avec un groupe sans parents
        $service    = $this->container->get('galerie.getTree');
        $folders    = $service->getJsonTree($droitRepo->findMainParent());
        
        //Pour l'instant on récupère que les albums, parce que j'arrive pas à imaginer une 'tain
        //de galerie
        //$albums = $albumRepo->findByDroit($droitRepo->findMainParent());
        
        $reponse    = array(
            
            'images'    => $albumRepo->findRandom(),
            'dossiers'  => $folders,
            //'albums'    => $albums,
            'enfants'   => $droitRepo->findDroitsEnfants($droitRepo->findMainParent()),
            'parent'    => array('nom' => null, 'id'  => null, 'color1' => null, 'color2' => null),
            'present'   => $droitRepo->findDroitData($droitRepo->findMainParent())
        );
        
        return new JsonResponse($reponse);
    }
    
    /**
     * la méthode getPictures retourne une liste d'images en fonction de l'id
     * de l'album souhaité
     */
    public function getPicturesAction($id) {
        
        $em         = $this->getDoctrine()->getManager();
        $albumRepo  = $em->getRepository('ExterneGalerieBundle:Album');
        
        return new JsonResponse(array(
            
            'images'    => $albumRepo->findPictures($id)
        ));
    }
    
    /**
     * updateDroit retourne au format JSON les informations pour remplir
     * la navigation avec les informations d'un groupe
     */
    public function updateDroitAction($id) {
        
        $em         = $this->getDoctrine()->getManager();
        $albumRepo  = $em->getRepository('ExterneGalerieBundle:Album');
        $droitRepo  = $em->getRepository('ExterneGalerieBundle:Droit');
        $droit      = $droitRepo->find($id);
        $gParent    = $droit->getGroupe()->getParent();
        $parent     = ($gParent == null) ? null : $droitRepo->findByGroupe($gParent)[0];
        
        $service    = $this->container->get('galerie.getTree');
        $folders    = $service->getJsonTree($droit);
        
        $reponse    = array(
            
            'dossiers'  => $folders,
            //'albums'    => $albumsRepo->findByDroit($droit),
            'enfants'   => $droitRepo->findDroitsEnfants($droit),
            'parent'    => array(
                
                            'nom' => ($parent == null) ? null : $gParent->getNom(),
                            'id'  => ($parent == null) ? null : $parent->getId(),
                            'color1' => ($parent == null) ? null : $parent->getColor1(),
                            'color2' => ($parent == null) ? null : $parent->getColor2()
                        ),
            
            'present'   => $droitRepo->findDroitData($droit)
        );
        
        return new JsonResponse($reponse);
    }
    
    /**
     * la fonction zipAlbum a pour rôle de zipper un album et de le renvoyer en
     * téléchargement
     */
    public function zipAlbumAction($id) {
        
        /*
        $albumRepo = $this->getDoctrine()->getManager()->getRepository('ExterneGalerieBundle:Album');
        
        //On récupère les photos
        $album = $albumRepo->find($id);
        $photos = $album->getPhotos();
        $groupe = $album->getDroit()->getGroupe()->getNom();
        $nom = $album->getNom();
        
        $zip = new \ZipArchive();
        $zipName = $album->getNom() . ".zip";
        $zip->open($zipName,  \ZipArchive::CREATE);
        $i = 0;
        
        foreach ($photos as $p) {
            
            $path = basename(urldecode($p['photo']));
            $webPath = 'photos/' . $groupe . '/' . $nom . '/originales/' .$path;
            $zip->addFromString($nom  . '-' . $i . '.jpg',  file_get_contents($webPath) );
            $i++;
        }
 
        $zip->close();
        
        $response = new Response();
        $response->setContent(readfile('../web/' . $zipName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->header('Content-disposition: attachment; filename="../web/'.$zipName.'"');
        $response->header('Content-Length: ' . filesize('../web/' .$zipName));
        $response->readfile('../web/' .$zipName);
        return $response;
        */
        
        $response = new Response();
        $response->setContent(readfile('../web/' . $zipName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->header('Content-disposition: attachment; filename="../web/'.$zipName.'"');
        $response->header('Content-Length: ' . filesize('../web/' .$zipName));
        $response->readfile('../web/' .$zipName);
        return $response;
    }
}
