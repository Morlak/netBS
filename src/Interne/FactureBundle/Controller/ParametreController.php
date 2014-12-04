<?php

namespace Interne\FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Interne\FactureBundle\Entity\Parametre;

class ParametreController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $parametres = $em->getRepository('InterneFactureBundle:Parametre')->findAll();


        $listeParametres = $this->listeParametre();

        /*
         * On controle que la liste est compléte. On ajoute à la BBD si nécaissaire
         */
        foreach($listeParametres as $parametre)
        {
            $found = false;
            foreach($parametres as $parametreBDD)
            {
                if($parametreBDD->getName() == $parametre['name'])
                {
                    $found = true;
                }
            }
            if(!$found)
            {
                $newParametre = new Parametre($parametre);
                $em->persist($newParametre);
                $parametres[] = $newParametre;
            }
        }
        $em->flush();

        return $this->render('InterneFactureBundle:Parametre:index.html.twig', array('parametres'=>$parametres));
    }

    public function updateAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $id = $request->request->get('id');
            $value = $request->request->get('value');
            $type = $request->request->get('type');

            $em = $this->getDoctrine()->getManager();
            $parametre = $em->getRepository('InterneFactureBundle:Parametre')->find($id);

            switch($type)
            {
                case 'string':
                    $parametre->setString($value);
                    break;
                case 'number':
                    $parametre->setNumber($value);
                    break;
                case 'text':
                    $parametre->setText($value);
                    break;
                case 'choice':
                    $parametre->setChoice($value);
                    break;
            }
            $em->flush();
            return new Response();
        }
        return new Response();

    }

    private function listeParametre()
    {
        /*
         * ICI est crée la liste des parametres
         */
        $listeParametres = array();
        /*
         *  QUELQUES EXEMPLES
         *
        $listeParametres[] = array('name'=>'Exemple_text','type'=>'text','labelName'=>'Text','value'=>'text');
        $listeParametres[] = array('name'=>'Exemple_string','type'=>'string','labelName'=>'String','value'=>'string');
        $listeParametres[] = array('name'=>'Exemple_number','type'=>'number','labelName'=>'Nombre','value'=>77);
        $listeParametres[] = array('name'=>'Exemple_choice','type'=>'choice','labelName'=>'choice','value'=>array('choice1','choice2'));
        */

        $listeParametres[] = array('name'=>'impression_ccp_bvr','type'=>'string','labelName'=>'Numéro de compte CCP (BVR)','value'=>'01-66840-7');
        $listeParametres[] = array('name'=>'impression_ccp_bv','type'=>'string','labelName'=>'Numéro de compte CCP (BV)','value'=>'10-1915-8');
        $listeParametres[] = array('name'=>'impression_adresse','type'=>'text','labelName'=>'Adresse du groupe scout','value'=>null);
        $listeParametres[] = array('name'=>'impression_mode_payement','type'=>'choice','labelName'=>'Choix du mode de payement','value'=>array('BV','BVR'));
        $listeParametres[] = array('name'=>'impression_texte_facture','type'=>'text','labelName'=>'Texte sur les factures','value'=>null);


        return $listeParametres;
    }





}