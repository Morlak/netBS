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
     * M�thode magique permettant de modifier l'attribut d'une entit� par requ�te ajax
     * le syst�me re�oit l'entit� ainsi que son nouveau contenu, la modifie, et c'est parti
     * la r�gle est :
     * bundle.entity.ID.truc.machin, par exemple InterneFichierBundle.famille.4.pere.contact.telephone
     * aussi appel�e LE MODIFIKATOR
     */
    public function modifikatorAction($entity, $id)
    {

        $request = $this->get('request');

        $content = $request->get('value');

        //Premi�re chose, on r�cup�re les informations sur l'entit�
        $entity = urldecode($entity);
        $data = explode('.', $entity);
        $bundle = $data[0];
        $entite = $data[1]; //Entit� m�re (famille, membre...)
        $field = $data[2];
        $link = array();

        //On formate le content en tant que boolean ou date si n�cessaire
/*
        if ($content == 'true') $content = true;
        else if ($content == 'false') $content = false;
        else if ($content == 'NULL_CONTENT') $content = null;
        else if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/", $content)) {
            $c = explode('.', $content);
            $content = new \Datetime($c[2] . '-' . $c[1] . '-' . $c[0]);
        } else {
            $content = urldecode($content);
        }
*/

        for ($i = 0; $i < count($data) - 2; $i++) {
            $link[$i] = $data[$i + 2]; //On stocke le chemin dans l'entit�
        }

        //On r�cup�re l'entit� avec l'em
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository($bundle . ':' . ucfirst($entite));

        $entity = $repo->find($id); //entity stock�e
        $res = $entity;
        $calls = count($link) - 1;


        //On parcourt ensuite l'entit� en fonction du nombre de param�tres pr�sents dans link

        for ($i = 0; $i < $calls; $i++) { //Le -1 parce que le dernier param�tre ne sera pas get mais set

            if ($calls != 0) {


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
