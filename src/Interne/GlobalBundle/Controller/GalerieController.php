<?php

namespace Interne\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Externe\GalerieBundle\Entity\Droit;
use Externe\GalerieBundle\Form\DroitType;

use Externe\GalerieBundle\Entity\Dossier;
use Externe\GalerieBundle\Entity\Album;
use Externe\GalerieBundle\Form\DossierType;
use Externe\GalerieBundle\Form\AlbumType;

class GalerieController extends Controller
{
    /**
     * permet de gérer la galerie
     * autoriser des groupes à avoir des albums, désactiver certains...
     * plein de trucs cools quoi
     */
    public function overviewAction()
    {
        $session        = new Session();
        $em             = $this->getDoctrine()->getManager();
        $groupesRepo    = $em->getRepository('InterneStructureBundle:Groupe');
        $droitsRepo     = $em->getRepository('ExterneGalerieBundle:Droit');
        
        $droit          = new Droit();
        $droitForm      = $this->createForm(new DroitType, $droit);
        
        if ($this->getRequest()->isMethod('POST')) {
		
		$droitForm->bind($this->getRequest());
		
		if ($droitForm->isValid()) {
			
		    //On persiste le nouveau groupe
                    $droit->setDroitAlbum(true);
                    $droit->setActive(true);
                    $droit->setAlbumsVisibles(true);
		    $em->persist($droit);
		    $em->flush();
		    $session->getFlashBag()->add('notice', 'Groupe inscrit avec succès');
                    return $this->redirect($this->generateUrl('InterneGlobal_galerie_groupe', array('id' => $id)));
		}
		
		else
		    $session->getFlashBag()->add('error', 'Erreur lors de l\'inscription du groupe');
	}
        
        //On récupère la liste des groupes totaux
        $listeDroits    = $droitsRepo->findAll();
        
        return $this->render('InterneGlobalBundle:Galerie:overview.html.twig', array(
                
                'droitForm'     => $droitForm->createView(),
                'listeDroits'   => $listeDroits
            )
        );
    }
    
    /**
     * la méthode groupeGalerieAction affiche la page de gestion de la galerie
     * d'un groupe
     */
    public function groupeGalerieAction($id) {
        
        //Récupération des droits du groupe
        $em          = $this->getDoctrine()->getManager();
        $dRepo       = $em->getRepository('ExterneGalerieBundle:Droit');
        $dossierRepo = $em->getRepository('ExterneGalerieBundle:Dossier');
        $droit       = $dRepo->find($id);
        $dossier     = new Dossier();
        $album       = new Album();
        $albumForm   = $this->createForm(new AlbumType($em, $droit), $album);
        $dossierForm = $this->createForm(new DossierType($em, $droit), $dossier);
        $session     = new Session();
        
        if ($this->getRequest()->isMethod('POST')) {
		
		$dossierForm->bind($this->getRequest());
		
		if ($dossierForm->isValid()) {
			
                    $dossier->setCreation(new \Datetime("now"));
                    $dossier->setAccess(true);
                    $dossier->setDroit($droit);
		    $em->persist($dossier);
                    $em->flush();
		    		    
		    $session->getFlashBag()->add('notice', 'Dossier ajouté');
                    return $this->redirect($this->generateUrl('InterneGlobal_galerie_groupe', array('id' => $id)));
		}
		
		else
		    $session->getFlashBag()->add('error', 'Erreur lors de l\'ajout du dossier');
	}
             
        return $this->render('InterneGlobalBundle:Galerie:groupe_galerie.html.twig', array(
            
                'droit'         => $droit,
                'dossierForm'   => $dossierForm->createView(),
                'albumForm'     => $albumForm->createView()
            )
        );
    }
    
    /**
     * la méthode retrieveTree se contente de renvoyer l'arbre des dossiers
     * et des albums pour affichage en ajax
     */
    public function retrieveTreeAction($id) {
        
        //Pour récupérer l'arbre, on récupère la liste des dossiers, puis pour chacun d'eux
        //On récupère les albums potentiels
        $droitsRepo = $this->getDoctrine()->getManager()->getRepository('ExterneGalerieBundle:Droit');
        $service    = $this->container->get('galerie.getTree');
        $dossiers   = $service->getJsonTree($droitsRepo->find($id));
        
        return new JsonResponse($dossiers);
    }
    
    /**
     * permet de mettre à jour les couleurs du groupe
     */
    public function updateColorsAction($id, $color1, $color2) {
        
        $em         = $this->getDoctrine()->getManager();
        $droitsRepo = $em->getRepository('ExterneGalerieBundle:Droit');
        $droit      = $droitsRepo->find($id);
        
        $droit->setColor1($color1);
        $droit->setColor2($color2);
        
        $em->persist($droit);
        $em->flush();
        
        return new JsonResponse(1);
    }
    
    /**
     * la méthode addAlbumAction va ajouter un nouvel album. La méthode s'occupe
     * donc de récupérer les informations, ainsi que l'ensemble des photos à mettre en base
     * de donnée et à uploader
     */
    public function addAlbumAction($id) {
        
        $session    = new Session();
        $em         = $this->getDoctrine()->getManager();
        $droitRepo  = $em->getRepository('ExterneGalerieBundle:Droit');
        $droit      = $droitRepo->find($id);
        
        $album      = new Album();
        $albumForm  = $this->createForm(new AlbumType($em, $droit), $album);
        
        if ($this->getRequest()->isMethod('POST')) {
		
		$albumForm->bind($this->getRequest());
		
		if ($albumForm->isValid()) {
                    
                    $album->setCreation(new \Datetime("now"));
                    $album->setDroit($droit);

		    $em->persist($album);
		    $em->flush();
                    
                    $return = array(
                        
                        'nom'   => $album->getNom(),
                        'id'    => $album->getId()
                    );
                    
                    return new JsonResponse($return);
		}
		
		else
                {
		    return new JsonResponse(false);
                }
	}
        
        else return new JsonResponse('grosse erreur');
    }
    
    /**
     * la méthode addPhotos permet d'ajouter tout plein de photos à un
     * album. On utilise dropzone.js pour l'envoi
     */
    public function addPhotosAction($id) {
        
        $request    = $this->get('request');
        $files      = $request->files;
        $em         = $this->getDoctrine()->getManager();
        $albumRepo  = $em->getRepository('ExterneGalerieBundle:Album');
        $album      = $albumRepo->find($id);
        $photos     = array();
        $c          = 0;
        $groupe     = $album->getDroit()->getGroupe()->getNom();
        
        if(is_null($files->get('photos'))) {
            
            return new JsonResponse('Rien à uploader');
            exit;
        }
        
        foreach ($files->get('photos') as $uploadedFile) {
            
            $path       = __DIR__ . '/../../../../web/photos/' . $this->removeAccents($groupe) . '/' . $this->removeAccents($album->getNom()) . '/originales/';
            $thumbPath  = __DIR__ . '/../../../../web/photos/' . $this->removeAccents($groupe) . '/' . $this->removeAccents($album->getNom()) . '/thumbnails/';
            
            if (!file_exists($thumbPath)) {
                mkdir($thumbPath, 0777, true);
            }
            
            /**
             * le nom du fichier enregistré compoprte 3 parties distinctes séparées par '__' :
             * l'id de l'album
             * le timestamp de l'upload
             * le md5 du nom original
             */
            $name       = $id . '__' . time() . '__'  . md5($uploadedFile->getClientOriginalName()) .  '.jpg';
            
            $file = $uploadedFile->move($path, $name);
            
            /**
             * on crée un thumbnail de l'image, de petite taille, pour l'affichage accéleré dans
             * la galerie
             */
            $desired_height = 200;
            $source_image   = imagecreatefromjpeg($path . $name);
            $width          = imagesx($source_image);
            $height         = imagesy($source_image);
            $desired_width  = floor($width * ($desired_height / $height));
            $virtual_image  = imagecreatetruecolor($desired_width, $desired_height);
            imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
            imagejpeg($virtual_image, $thumbPath .'t__' . $name);
            
            //Changer le répertoire
            $photos[$c]['photo']       = urlencode('/netBS/web/photos/' . $this->removeAccents($groupe) . '/' . $this->removeAccents($album->getNom()) . '/originales/' . $name);
            $photos[$c]['thumbnail']   = urlencode('/netBS/web/photos/' . $this->removeAccents($groupe) . '/' . $this->removeAccents($album->getNom()) . '/thumbnails/' . 't__' . $name);
            $c++;
            
        }
        
        //On ajoute les nouvelles photos aux anciennes
        $album->setPhotos($photos);
        $em->persist($album);
        $em->flush();
        
        return new Response('');
    }
    
    private function removeAccents($str) {
        
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
        $modified = str_replace($a, $b, $str);
        
        $string = str_replace(' ', '-', $modified); // Replaces all spaces with hyphens
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}
